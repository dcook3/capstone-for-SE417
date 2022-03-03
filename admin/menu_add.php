<?php
    $levels = 1;
    include("..\models\lucas.php");
    
    $post = false;
    if($_SERVER['REQUEST_METHOD']==='POST'){
        if(isset($_POST["id"])){
            $post = true;
            $item = Menu_Item::getMenuItemByID($_POST["id"], true);
            $item->populateImage();
        }
    }
    include '../include/header.php';
    Session::CheckSession();
?>
<script  src="../models/lucas.js"></script>
<link rel="stylesheet" href="assets/css/lucas.css">
<table class = "hidden">
    <tbody>
        <tr class = "ingredientRow" id = "templateRow" data-id = "-1">
            <td><input class = "form-control" type = "text" placeholder ="Name"></td>
            <td><input class = "form-control" type="number" min="0.00" max="10000.00" step="0.01" placeholder="Price"/></td>
            <td><div class="isDefaultWrapper"><label for = "isDefault">Default</label><input type = "checkbox" name = "isDefault" /></div></td>
            <td><button class = "btn btn-secondary deleteButton" onclick="deleteRow(this.parentElement.parentElement)"><i class="fas fa-trash-alt"></i></button></td>
        </tr>
    </tbody>
</table>
<div class="d-flex justify-content-around flex-row">
    <div id="formWrapper">
        <div id="topWrapper">
            <button id = "backBtn" class = "btn btn-secondary" onclick = "window.location.replace('menu_view.php')" data-id = "<?= ($post) ? $item->getMenuItemId() : ""?>">Back</button>
            <h1><?= ($post) ? "Edit" : "Add New" ?> Item</h1>
            <p></p>
        </div>
        <form>
            <div class="form-group">
                <label for = "nameInput" class = "form-label">Item Name:</label>
                <input id = "nameInput" name = "nameInput" class = "form-control" type = "text" <?= ($post) ? "value = '{$item->getItemName()}'" : ""?>>
            </div>
            <div class="form-group">
                <label for = "selectInput" class = "form-label">Section:</label>
                <select id = "selectInput" class = "form-control">
                    <?php 
                        foreach(Section::getSections() as $section){
                            if($post && $section->getSectionId() == $item->getSection()->getSectionId()){
                                echo "<option selected value = ".$item->getSection()->getSectionId().">". $section->getSectionName() . "</option>";
                            }
                            else{
                                echo "<option value = ".$section->getSectionId().">". $section->getSectionName() . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for = "descriptionInput" class = "form-label">Description:</label>
                <textarea name = "descriptionInput" id = "descriptionInput" class = "form-control"><?= ($post) ? $item->getItemDescription() : ""?></textarea>
            </div>
            <div class="form-group">
                <label for = "priceInput" class = "form-label">Price:</label>
                <input name = "priceInput" class = "form-control" id = "priceInput" type="number" min="0.00" max="10000.00" step="0.01" <?= ($post) ? "value = '{$item->getItemPrice()}'" : ""?> />
            </div>
            <div class = "form-group special-group">
                <label for = "descriptionInput" class = "form-label">Special Item:</label> 
                <input name = "specialInput" class = "form-check-input" id = "specialInput" type="checkbox" <?= ($post && $item->getIsSpecial()) ? "checked" : "" ?>/>
            </div>
            <div class = "form-group">
                <label for = "descriptionInput" class = "form-label">Item Image:</label>
                <input name = "fileUpload" class = "form-control" id = "fileUpload" type="file" accept="image/*"/>
            </div>
            <div class="form-group <?= (!$post || $item->getItemImg() == null || $item->getItemImg() == "") ? "hidden": ""?>">
                <label class = "form-label">Preview:</label>
                <img id = "previewImg" src = "<?= ($post) ? $item->getItemImg() : "" ?>">
            </div>
        </form>
    </div>
    <div id="ingredientWrapper">
        <table id = "ingredientTable" class = "table table-hover table-striped text-center">
            <thead id = "ingredientHeader">
                <tr>
                    <td>
                        <h2>Ingredients</h2>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <button id = "addIngredientBtn" class = "btn btn-secondary">+</button>
                    </td>
                </tr>
                
            </thead>
        
            <tbody id = "ingredientBody">
                <?php 
                    if($post){
                        foreach($item->getIngredients() as $key=>$ingredient){
                            $isDefaultStr = ($ingredient->getIsDefault()) ? "checked" : ""; 
                            ?>
                            <tr class = 'ingredientRow' data-id = '<?=$ingredient->getIngredientId()?>'>
                                <td><input class = "form-control" type = 'text' placeholder ='Name' value = '<?=$ingredient->getIngredientName()?>'/></td>
                                <td><input class = "form-control" type='number' min='0.00' max='10000.00' step='0.01' value = '<?=$ingredient->getIngredientPrice()?>'/></td>
                                <td>
                                    <div class='isDefaultWrapper'>
                                        <label for = 'isDefault'>Default</label>
                                        <input type = 'checkbox' name = 'isDefault' <?= $isDefaultStr ?>/>
                                    </div>
                                </td>
                                <td>
                                    <button id = 'deleteButton' class = 'btn btn-secondary' onclick='deleteRow(this.parentElement.parentElement)'><i class='fas fa-trash-alt'></i></button>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                ?>
            </tbody>
        </table>
        <div id="doneBtnWrapper">
            <p></p>
            <button id = "doneBtn" class = "btn btn-secondary">Done</button>
        </div>
    </div>
    <div class="modal fade" id="croppingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cropper-container">
                    <img id = "croppedImage">
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="cancelCrop()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="cropImage()">Done</button>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
    var backBtn = document.querySelector("#backBtn")
    var addIngredientBtn = document.querySelector("#addIngredientBtn")
    var ingredientBody = document.querySelector("#ingredientBody")
    var templateRow = document.querySelector("#templateRow")
    var doneBtn = document.querySelector("#doneBtn")
                
    //form inputs
    var nameInput = document.querySelector("#nameInput");
    var selectInput = document.querySelector("#selectInput");
    var descriptionInput = document.querySelector("#descriptionInput");
    var priceInput = document.querySelector("#priceInput");
    var specialInput = document.querySelector("#specialInput");
    var fileUpload = document.querySelector("#fileUpload");
    var image = document.querySelector("#croppedImage");
    var previewImg = document.querySelector("#previewImg");
    var cropper = new Cropper(image, {
        dragMode: 'move',
        aspectRatio: 16 / 9,
        autoCropArea: 0.65,
        restore: false,
        guides: false,
        center: true,
        highlight: false,
        cropBoxMovable: false,
        cropBoxResizable: false,
        toggleDragModeOnDblclick: false,
      });
    var modal = new bootstrap.Modal(document.querySelector("#croppingModal"));
    if("<?= $post?>" == "1"){
        post = true;
    }
    else{
        post = false;
    }
    function cancelCrop(){
        fileUpload.value = "";
    }
    function cropImage(){
        previewImg.src = cropper.getCroppedCanvas().toDataURL("image/jpeg");
        previewImg.parentElement.classList.remove("hidden");
        modal.hide();
    }
    fileUpload.addEventListener("change", function(e){
        
        modal.show();
      
      var reader = new FileReader();
      reader.addEventListener("load", function(e){
         cropper.replace(e.target.result);
      })
      reader.readAsDataURL(fileUpload.files[0]);
      fileUpload.value = "";
      
      
    })

    backBtn.addEventListener("click", function(e){
        window.location.replace("menu_view.php")
    })

    addIngredientBtn.addEventListener("click", function(e){
        addRow();
    });
    doneBtn.addEventListener("click", async function(e){
        let selectedOption = selectInput.children[selectInput.selectedIndex];
        item = new Menu_Item((post) ? backBtn.dataset["id"] : "-1", 
                                new Section(selectedOption.value, selectedOption.innerText),
                                nameInput.value,
                                descriptionInput.value,
                                priceInput.value, 
                                previewImg.src,
                                specialInput.checked)
        for(let i = 0; i < ingredientBody.children.length; i++){
            let ingredientRow = ingredientBody.children[i];
            let price = (ingredientRow.children[1].children[0].value == "") ? "0" : ingredientRow.children[1].children[0].value;
        
            item.addIngredient(new Ingredient(ingredientRow.dataset["id"],
                                                ingredientRow.children[0].children[0].value,
                                                price,
                                                ingredientRow.children[2].children[0].children[1].checked))
            
        }
        if(!post){
            item.addToDatabase();
        }
        else{
            item.updateItem();
        }
        setTimeout(function(){
            window.location.replace("menu_view.php")
        }, 10);
    })

    function addRow(){
        ingredientBody.appendChild(templateRow.cloneNode(true))
    }
    function deleteRow(row){
        if(row.dataset["id"] != "-1" && confirm("This action is permenant do you wish to proceed")){
            ingredientBody.removeChild(row);
            if(row.dataset["id"] != "-1" || row.dataset["id"] != ""){
                Ingredient.deleteIngredient(row.dataset["id"], backBtn.dataset["id"])
            }
        }
        else{
            ingredientBody.removeChild(row);
        }
        
    }
    
    


</script>
<?php include '../include/footer.php'; ?>