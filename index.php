<?php
include("includes/front/top.php");
include("includes/front/header_static.php");
include("includes/models/lucas.php");

$sections = Section::getSections();
?>
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>

<link rel ="stylesheet" href = "main_lucas.css">

<div class="hidden" id = "user_id" data-id = "<?= isset($_SESSION['USER']) ? $_SESSION['USER']->user_id : "-1"?>"></div>
<!-- TEMPLATE ELEMENTS -->
<div id = "templateCard" class = "card bg-primary text-white itemCard hidden">
    <img class = "cardImage" src = "">
    <div class="card-body">
        <div class = "cardTop">
            <h1 class = "card-title"></h1>
            <p class = "cardPrice"></p>
        </div>
        <p class = "cardDescription"></p>
        <div class = "plsBtnFlex">
            <div></div>
            <button id = "btn" class = "btn btn-secondary">+</button>
        </div>
    </div>
    
</div>
<li id = "templateIngredient" class = "hidden">
    <input type = "checkbox">
    <p></p>
</li>


<div id="banner">
    <div id = "listViewDiv">
        <div  class="form-switch">
            <input type="checkbox" class="form-check-input " data-onstyle = "secondary" id="listView">
            <label class="form-check-label" for="listView">List View</label>
        </div>
    </div>
    
    <p id = "specialTag">Special</p>
    <div id="slideshow">
        <?php 
            $i = 0;
            
            foreach(Menu_Item::getSpecialItems() as $specialItem){
                
                $specialItem->populateImage();
                if($specialItem->getItemImg() != "" || $specialItem->getItemImg() != NULL)
        ?>
                <div class="slide <?= ($i > 0) ? "hidden" : ""?>">
                    <img data-id = "<?= $specialItem->getMenuItemId()?>" src = "<?= $specialItem->getItemImg()?>">
                </div>

        <?php
            $i++;
            }
        ?>
    </div>
    <div id = "addItemImg" class = "hidden">
        <img>
    </div>
    

</div>


<div id="sectionCards">
    <?php
        foreach($sections as $section){
    ?>
        <div class="card text-white bg-primary sectionCard" onclick = "sectionClick('<?= $section->getSectionId()?>')">
        <img class = "cardImage" src = "<?= $section->getSectionImg(); ?>">
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
<div id="addItemWrapper">
    <form id = "addItemMenu" class = "hidden">
        <div class="form-group">
            <h1>

            </h1>
        </div>
        <div class="form-group">
            <ul id = "ingredients">
                <li id = "ingredientHeader">
                    <h2>Ingredients</h2>
                </li>
            </ul>
        </div>
        <div class="form-group">
            <div>
                <label>Quantity</p>
                <div class="quantitySelector  d-flex flex-row justify-content-around align-items-center input-group">
                    <input type="button" class="btn btn-secondary" id="button-addon1" value="-">
                    <input id = "qtyInput" type="number" class="form-control" min="1" class="quantity" data-action="updateQuantity" value="1">
                    <input type="button" class="btn btn-secondary" id="button-addon2" value="+">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div>
                <label>Extra Notes:</label>
                <textarea id = "notesInput" class = "form-control"></textarea>
            </div>
        </div>
        
    </form>
    <div id = "btnGroup">
        <button id = "addToCartBtn" class = 'btn btn-secondary hidden'>
            <span>Add To Cart</span>
            <span></span>
        </button>
    </div>
    
    <button type="button" class="btn btn-secondary btn-circle" id = "cartBtn" onclick=" (user_id == '-1') ? window.location.replace('login.php') : window.location.replace('cart.php')">
    <img src = "cart-shopping-solid.svg">
    </button>
    <div class="modal fade" id="croppingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order already placed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You already have an order placed. Go to the cart page to see the status of your order.</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="modal.hide()">Later</button>
                <button type="button" class="btn btn-primary" onclick="window.location.replace('cart.php')">Go Now?</button>
            </div>
            </div>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/4933cad413.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src = "includes/models/lucas.js"></script>
<script>
    var sectionCards = document.querySelector("#sectionCards");
    var itemCards = document.querySelector("#itemCards");
    var templateCard = document.querySelector("#templateCard");
    var templateIngredient = document.querySelector("#templateIngredient");
    var addItemMenu = document.querySelector("#addItemMenu");
    var qtyInput = document.querySelector("#qtyInput");
    var menuItems
    var user_id = document.querySelector("#user_id").dataset["id"]
    var item
    var addToCartBtn = document.querySelector("#addToCartBtn");
    var cartBtn = document.querySelector("#cartBtn");
    var notesInput = document.querySelector("#notesInput")
    var btn = document.querySelector("#btn");
    var backBtn = document.querySelector("#backBtn");
    var slides = document.querySelectorAll(".slide");
    var slideshow = document.querySelector("#slideshow");
    var addItemImg = document.querySelector("#addItemImg");
    var slideIndex = 0;
    var specialTag = document.querySelector("#specialTag");
    var plsBtn = document.querySelector("#button-addon2")
    var mnsBtn = document.querySelector("#button-addon1")
    var ingredientsUL = document.querySelector("#ingredients");
    var templateIngredientsUL = ingredientsUL.cloneNode(true);
    var listView = document.querySelector("#listView");
    var modal;
    class States{
        static Section = new States("section");
        static Items = new States("items");
        static Add = new States("add");
        static AddSpecial = new States("AddSpecial");

        constructor(name){
            this.name = name;
        }
    }
    var state = States.Section;

    function calcPrice(){
        price = item.item_price;
        for(let i = 0; i < ingredientsUL.children.length; i++){
            let ingredient = ingredientsUL.children[i]
            if(ingredient.children[0].checked){
                price += parseFloat(ingredient.dataset["ingredientPrice"])
            }
        }
        price = price * parseInt(qtyInput.value);
        addToCartBtn.children[1].innerHTML = "$" + price.toFixed(2);
    }
    
    function gotoAddItemMenu(_item){
        if(user_id == "-1"){
            window.location.replace("login.php");
        }
        else
        {
            addItemMenu.querySelectorAll("grammarly-extension").forEach((item) =>{
                addItemMenu.removeChild(item);
            })
            item = _item
            ingredientsUL.innerHTML = templateIngredientsUL.innerHTML;
            addItemMenu.children[0].children[0].innerHTML = item.item_name;
            addToCartBtn.children[1].innerHTML = "$" + item.item_price;
            qtyInput.value = 1;
            notesInput.value =  "";
            for(let y = 0; y < item.ingredients.length; y++){
                let ingredient = templateIngredient.cloneNode(true);
                ingredient.dataset["ingredientPrice"] = item.ingredients[y].ingredient_price;
                ingredient.children[0].dataset["id"] = item.ingredients[y].ingredient_id
                ingredient.classList.remove("hidden");
                ingredient.children[1].innerHTML = item.ingredients[y].ingredient_name + ((item.ingredients[y].ingredient_price > 0) ? "(" + item.ingredients[y].ingredient_price + ")" : ""); 
                addItemMenu.children[1].children[0].appendChild(ingredient);
                ingredient.children[0].checked = ingredient.is_default;
                ingredient.children[0].addEventListener("change", function(e){
                   calcPrice();
                })
            }
            addItemImg.children[0].src = item.item_img;
            listView.parentElement.parentElement.classList.add("hidden");
            cartBtn.classList.add("hidden");
            addToCartBtn.classList.remove("hidden");
            specialTag.classList.add("hidden");
            backBtn.classList.remove("btn-hidden");
            slideshow.classList.add("hidden");
            addItemImg.classList.remove("hidden")
            sectionCards.classList.remove("hidden");
            sectionCards.classList.add("hidden");
            itemCards.classList.remove("hidden");
            itemCards.classList.add("hidden");
            addItemMenu.classList.remove("hidden");
        }
    }
    
    function sectionClick(id){
        Menu_Item.getMenuItemsBySectionId(id, true, function(_menuItems){
            state = States.Items;
            backBtn.classList.remove("btn-hidden");
            menuItems = _menuItems
            itemCards.innerHTML = "";
            for(let i = 0; i < menuItems.length; i++){
                itemCards.appendChild(templateCard.cloneNode(true));
                let card = itemCards.children[i].children[1];
                card.parentElement.classList.remove("hidden");
                card.parentElement.children[0].src = menuItems[i].item_img;
                if(listView.checked){
                    card.parentElement.children[0].classList.add("hidden");                    
                }
                card.children[0].children[0].innerHTML = menuItems[i].item_name;
                card.children[0].children[1].innerHTML = "$" + menuItems[i].item_price;
                card.children[1].innerHTML = menuItems[i].item_description;
                card.children[2].children[1].setAttribute("data-index", i);
                card.children[2].children[1].addEventListener("click", function(e){
                    state = States.Add;
                    gotoAddItemMenu(menuItems[e.target.getAttribute("data-index")])
                    
                })
            }
            
            sectionCards.classList.add("hidden");
            itemCards.classList.remove("hidden");
        })
        
    }
    
    async function showSlides(){
        slides[slideIndex].classList.add("hidden");
        if(slideIndex < slides.length-1){
            slideIndex+= 1;
        }
        else{
            slideIndex = 0;
        }
        slides[slideIndex].classList.remove("hidden");
        setTimeout(showSlides, 7000)
    }

    window.addEventListener('load', (event) => {
        modal = new bootstrap.Modal(document.querySelector("#croppingModal"));
    });
    listView.addEventListener("click", function(e){
        console.log(e.target);
        var imgs = document.querySelectorAll(".cardImage")

        if(e.target.checked){
            for(let i = 0; i < imgs.length; i++){
                imgs[i].classList.add("hidden");
            }
        }
        else{
            for(let i = 0; i < imgs.length; i++){
                imgs[i].classList.remove("hidden");
            }
        }
    })
    plsBtn.addEventListener("click", function(e){
        qtyInput.value = parseInt(qtyInput.value) + 1;
        calcPrice();
    })
    mnsBtn.addEventListener("click", function(e){
        if(parseInt(qtyInput.value) > 1)
        {
            qtyInput.value = parseInt(qtyInput.value) - 1;
        }
        else{
            qtyInput.value = 1;
        }
        calcPrice();
    })
    addToCartBtn.addEventListener("click", function(e){
        Order.createOrderIfNoneExists(user_id, function(order){
            if(order.order_status != 1){

                
                let order_item = new Order_Item(item.menu_item_id,
                                                item.section,
                                                item.item_name, 
                                                item.item_description, 
                                                item.item_price, 
                                                item.item_img,
                                                item.is_special,
                                                "-1",
                                                qtyInput.value,
                                                notesInput.value)
                for(let i = 1; i < addItemMenu.children[1].children[0].children.length; i++){
                    console.log(i);
                    let checkbox = addItemMenu.children[1].children[0].children[i].children[0]
                    if(checkbox.checked){
                        order_item.addIngredient(new Ingredient(checkbox.dataset["id"], "0",checkbox.parentElement.dataset["ingredientPrice"], false))
                    }
                }
                window.location.replace("cart.php")
                order.addOrderItem(order_item, function(data){console.log(data)});
                window.location.replace('cart.php')
            }
            else{
                modal.show();
            }
        });
    })

    backBtn.addEventListener("click", function(e){
        switch(state){
            case States.Items:
                backBtn.classList.add("btn-hidden");
                sectionCards.classList.remove("hidden");
                itemCards.classList.add("hidden");
                state = States.Section;
                break
            case States.Add:
                listView.parentElement.parentElement.classList.remove("hidden");
                cartBtn.classList.remove("hidden");
                addToCartBtn.classList.add("hidden")
                addItemImg.classList.add("hidden");
                specialTag.classList.remove("hidden");
                slideshow.classList.remove("hidden");
                itemCards.classList.remove("hidden");
                addItemMenu.classList.add("hidden");
                state = States.Items;
                break
            case States.AddSpecial:
                listView.parentElement.parentElement.classList.remove("hidden");
                cartBtn.classList.remove("hidden");
                addToCartBtn.classList.add("hidden")
                addItemImg.classList.add("hidden");
                specialTag.classList.remove("hidden");
                slideshow.classList.remove("hidden");
                itemCards.classList.add("hidden");
                sectionCards.classList.remove("hidden");
                backBtn.classList.add("btn-hidden");
                addItemMenu.classList.add("hidden");
                state = States.Section;
                break
        }
    })
    for(let i = 0; i < slides.length; i++){
        slides[i].children[0].addEventListener("click", function(e){
            Menu_Item.getMenuItemByID(e.target.dataset["id"], false, function(menuItem){
                state = States.AddSpecial;
                menuItem.item_img = e.target.src;
                gotoAddItemMenu(menuItem);
            })
        })
    }

    
    showSlides();
</script>
<?php include("includes/front/footer.php"); ?>
