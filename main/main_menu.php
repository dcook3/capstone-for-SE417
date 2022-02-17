<?php
include("header.php");
include("../models/lucas.php");

$sections = Section::getSections();

?>

<div class="slideshowWrapper">

</div>

<div id="menuCards">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src = "../models/lucas.js"></script>
<script>
    var menuCards = document.querySelector("#menuCards");
    function emptyMenuCards(){
        
        while(menuCards.children.length > 0){
            console.log("hello")
            menuCards.removeChild(menuCards.children[0])
        }
    }

    function sectionClick(id){
        Menu_Item.getMenuItemsBySectionId(id, function(menuItems){
            console.log(menuItems);
            emptyMenuCards();
        })
    }


    
</script>
</body>
</html>
