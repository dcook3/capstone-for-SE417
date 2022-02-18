<?php
include("header.php");
include("../models/lucas.php");

$sections = Section::getSections();

?>
<style>
    .hidden{
        display: none;
    }
</style>
<div id = "templateCard" class = "card itemCard hidden">
    <h1 class = "card-title"></h1>
    <p></p>
    <button id = "btn">+</button>
</div>
<div class="slideshowWrapper">

</div>

<div id="sectionCards">
    <?php
        foreach($sections as $section){
    ?>
        <div class="card sectionCard" onclick = "sectionClick('<?= $section->getSectionId()?>')">
            <h1 class = "card-title"><?= $section->getSectionName()?></h1>
        </div>

    <?php
        }
    ?>

</div>

<div id = "itemCards" class = "hidden">
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src = "../models/lucas.js"></script>
<script>
    var sectionCards = document.querySelector("#sectionCards");
    var itemCards = document.querySelector("#itemCards");
    var templateCard = document.querySelector("#templateCard");
    var menuItems
    var btn = document.querySelector("#btn");
    function sectionClick(id){
        Menu_Item.getMenuItemsBySectionId(id, function(_menuItems){
            menuItems = _menuItems
            for(let i = 0; i < menuItems.length; i++){
                itemCards.appendChild(templateCard.cloneNode(true));
                let card = itemCards.children[i];
                card.classList.remove("hidden");
                card.children[0].innerHTML = menuItems[i].item_name;
                card.children[1].innerHTML = menuItems[i].item_description;
                card.children[2].setAttribute("data-index", i);
                card.children[2].addEventListener("click", function(e){
                    console.log(menuItems[e.target.getAttribute("data-index")])
                })
            }
            
            sectionCards.classList.add("hidden");
            itemCards.classList.remove("hidden");
        })
    }
    function selectItem(){
        console.log(i);
    }

    
</script>
</body>
</html>
