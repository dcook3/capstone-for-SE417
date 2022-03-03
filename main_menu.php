<?php
include("header.php");
include("models/lucas.php");
include("include/login.php");

$sections = Section::getSections();

?>
<?php
    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        Session::destroy();
    }
?>

<link rel ="stylesheet" href = "main_lucas.css">


<!-- TEMPLATE ELEMENTS -->
<div id = "templateCard" class = "card bg-primary text-white itemCard hidden">
    <img src = "">
    <div class="card-body">
        <h1 class = "card-title"></h1>
        <p></p>
        <button id = "btn">+</button>
    </div>
    
</div>
<li id = "templateIngredient" class = "hidden">
    <input type = "checkbox">
    <p></p>
</li>


<div id="banner">
    <a id = "backBtn" class = "hidden">
        <i class="fa fas fa-arrow-left"></i>
    </a>
    <p>Special</p>
    <div id="slideshow">
        <?php 
            foreach(Menu_Item::getSpecialItems() as $specialItem){
                $specialItem->populateImage();
                if($specialItem->getItemImg() != "" || $specialItem->getItemImg() != NULL)
        ?>
                <div class="slide">
                    <img src = "<?= $specialItem->getItemImg()?>">
                </div>

        <?php
            }
        ?>
    </div>
</div>


<div id="sectionCards">
    <?php
        foreach($sections as $section){
    ?>
        <div class="card text-white bg-primary sectionCard" onclick = "sectionClick('<?= $section->getSectionId()?>')">
        <img src = "<?= $section->getSectionImg(); ?>">
        <div class="card-body">
                <h1 class = "card-title"><?= $section->getSectionName()?></h1>
            </div>    
        </div>
    <?php
        }
    ?>

</div>

<div id = "itemCards" class = "hidden">

</div>

<div id = "addItemMenu" class = "hidden">
    <h1>

    </h1>
    <ul>
        <li>
            <h2>Ingredients</h2>
        </li>
    </ul>
    <div>
        <p>Quantity</p>
        <input id = "qtyInput" type = "number" min = "1" max = "10" value = "1">
    </div>
    <div class="priceInfo">
        <p>Price: </p>
        <p></p>
    </div>
    <button id = "addToCartBtn">
        Add to Cart
    </button>
</div>

<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src = "models/lucas.js"></script>
<script>
    var sectionCards = document.querySelector("#sectionCards");
    var itemCards = document.querySelector("#itemCards");
    var templateCard = document.querySelector("#templateCard");
    var templateIngredient = document.querySelector("#templateIngredient");
    var addItemMenu = document.querySelector("#addItemMenu");
    var qtyInput = document.querySelector("#qtyInput");
    var menuItems
    var item
    var addToCartBtn = document.querySelector("#addToCartBtn");
    var btn = document.querySelector("#btn");
    var backBtn = document.querySelector("#backBtn");
    class States{
        static Section = new States("section");
        static Items = new States("items");
        static Add = new States("add");

        constructor(name){
            this.name = name;
        }
    }
    var state = States.Section;
    function sectionClick(id){
        Menu_Item.getMenuItemsBySectionId(id, true, function(_menuItems){
            state = States.Items;
            backBtn.classList.remove("hidden");
            menuItems = _menuItems
            for(let i = 0; i < menuItems.length; i++){
                itemCards.appendChild(templateCard.cloneNode(true));
                let card = itemCards.children[i].children[1];
                card.parentElement.classList.remove("hidden");
                card.parentElement.children[0].src = menuItems[i].item_img;
                card.children[0].innerHTML = menuItems[i].item_name;
                card.children[1].innerHTML = menuItems[i].item_description;
                card.children[2].setAttribute("data-index", i);
                card.children[2].addEventListener("click", function(e){
                    state = States.Add
                    item = menuItems[e.target.getAttribute("data-index")]
                    addItemMenu.children[0].innerHTML = item.item_name;
                    for(let y = 0; y < item.ingredients.length; y++){
                        let ingredient = templateIngredient.cloneNode(true);
                        ingredient.classList.remove("hidden");
                        ingredient.children[0].dataset["id"] = item.ingredients[y].ingredient_id
                        ingredient.children[1].innerHTML = item.ingredients[y].ingredient_name + ((item.ingredients[y].ingredient_price > 0) ? "(" + item.ingredients[y].ingredient_price + ")" : ""); 
                        addItemMenu.children[1].appendChild(ingredient);
                        ingredient.children[0].checked = ingredient.is_default;
                        ingredient.children[0].addEventListener("change", function(e){
                            let price =  parseFloat(addItemMenu.children[2].children[1].innerHTML);
                            if(e.target.checked){
                                price += item.ingredients[y].ingredient_price;
                            }
                            else{
                                price -= item.ingredients[y].ingredient_price;
                            }
                            addItemMenu.children[2].children[1].innerHTML = price;
                        })
                    }
                    
                    addItemMenu.children[2].children[1].innerHTML = item.item_price;
                    
                    
                    itemCards.classList.add("hidden");
                    addItemMenu.classList.remove("hidden");
                })
            }
            
            sectionCards.classList.add("hidden");
            itemCards.classList.remove("hidden");
        })
    }
    addToCartBtn.addEventListener("click", function(e){
        Order.createOrderIfNoneExists("6", function(order){
            let order_item = new Order_Item(item.menu_item_id,
                                            item.section,
                                            item.item_name, 
                                            item.item_description, 
                                            item.item_price, 
                                            item.item_img,
                                            "-1",
                                            qtyInput.value)
            for(let i = 1; i < addItemMenu.children.length; i++){
                let checkbox = addItemMenu.children[1].children[i].children[0]
                if(checkbox.checked){
                    order_item.addIngredient(new Ingredient(checkbox.dataset["id"], "0", "0", false))
                }
            }
            order.addOrderItem(order_item, function(){window.location.replace("cart.php")});

        });
    })
    function selectItem(){
        console.log(i);
    }
    backBtn.addEventListener("click", function(e){
        switch(state){
            case States.Items:
                backBtn.classList.add("hidden");
                sectionCards.classList.remove("hidden");
                itemCards.classList.add("hidden");
                state = States.Section;
                break
            case States.Add:
                itemCards.classList.remove("hidden");
                addItemMenu.classList.add("hidden");
                state = States.Items;
                break
        }
    })
    
</script>
</body>
</html>
