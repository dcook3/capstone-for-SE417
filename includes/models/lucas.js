function count(str, find) {
    return (str.split(find)).length - 1;
}
var rootPath = "";
if(count(window.location.pathname, "/") == 2){
    rootPath = "../../"
}
else{
    rootPath = "../"
}
class User{
    constructor(user_id, email, fname, lname, phone, student_id){
        this.user_id = user_id;
        this.email = email;
        this.fname = fname;
        this.lname = lname;
        this.phone = phone;
        this.student_id = student_id;
    }

    updateUser(){
        $.ajax({
            url:rootPath+"/as_capstone/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'updateUser',
                'user'   : user
            }
        })
        .fail(function(e){console.log("An error occured on lucas.js")})
        .done(function(data){
            console.log(data);
        })
    }
}

class Menu_Item{
    constructor(menu_item_id, section, item_name, item_description, item_price, item_img, is_special){
        this.menu_item_id = menu_item_id;
        this.section = section;
        this.item_name = item_name;
        this.item_description = item_description;
        this.item_img = item_img;
        this.item_price = item_price;
        this.is_special = is_special;
        this.ingredients = new Array();
    }
    addIngredient(ingredient){
        this.ingredients.push(ingredient);
        return(this.ingredients)
    }
    addToDatabase(){
        $.ajax({
            url : rootPath+"/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'addToDB',
                'item' : this,
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            return(data);
        })
    }
    updateItem(){
        $.ajax({
            url : rootPath+"/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'updateItem',
                'item' : this,
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            return(data);
        })
    }
    static async deleteItem(menu_item_id){
        let data = await $.ajax({
            url : rootPath+"/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'deleteItem',
                'menu_item_id' : menu_item_id
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            return(data);
        })
        return data.trim();
    }
    static getMenuItemByID(menu_item_id, wthImg, callback){
        $.ajax({
            url : rootPath+"/as_capstone/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'getMenuItemByID',
                'menu_item_id' : menu_item_id,
                'wthImg': wthImg
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            var d = JSON.parse(data);
            
            let menuItem = new Menu_Item(d['menu_item_id'], new Section(d['section']['section_id'], d['section']['section_name'], d['section']['section_img']), d['item_name'], d['item_description'], d['item_price'], d['item_img'], d['is_special'])
            for(let y = 0; y < d['itemIngredients'].length; y++){
                let ingredient = d['itemIngredients'][y]
                menuItem.addIngredient(new Ingredient(ingredient['ingredient_id'], ingredient['ingredient_name'], ingredient['ingredient_price'], ingredient['is_default']))
            }
            callback(menuItem)
            
        })
    }
    static async getMenuItemsBySectionId(section_id, wthImg, callback){
        $.ajax({
            url : rootPath+"/as_capstone/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'getMenuItemsBySectionId',
                'section_id' : section_id,
                'wthImg': wthImg
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            var data = JSON.parse(data);
            var menuItems = Array();

            for(let i = 0; i < data.length; i++){
                let d = data[i];
                menuItems[i] = new Menu_Item(d['menu_item_id'], new Section(d['section']['section_id'], d['section']['section_name'], d['section']['section_img']), d['item_name'], d['item_description'], d['item_price'], d['item_img'], d['is_special'])
                for(let y = 0; y < d['itemIngredients'].length; y++){
                    let ingredient = d['itemIngredients'][y]
                    menuItems[i].addIngredient(new Ingredient(ingredient['ingredient_id'], ingredient['ingredient_name'], ingredient['ingredient_price'], ingredient['is_default']))
                }
            }

            callback(menuItems)
            
        })
    }
    
}

class Order_Item extends Menu_Item{
    constructor(menu_item_id, section, item_name, item_description, item_price, item_img, is_special, order_item_id, qty, item_notes){
        super(menu_item_id, section, item_name, item_description, item_price, item_img, is_special);
        this.order_item_id = order_item_id;
        this.qty = qty;
        this.item_notes = item_notes
        this.ingredients = Array();
    }
}

class Order{
    constructor(order_id, user_id, order_status){
        this.order_id = order_id;
        this.user_id = user_id;
        this.order_status = order_status;
        this.order_items = Array();
    }
    addOrderItem(order_item, callback){
        $.ajax({
            url : rootPath+"/as_capstone/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'addOrderItem',
                'order_id': this.order_id,
                'order_item' : order_item,
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            callback(data)
        })
    }
    static createOrderIfNoneExists(user_id, callback){
        $.ajax({
            url : rootPath+"/as_capstone/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'createOrderIfNoneExists',
                'user_id' : user_id,
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            console.log(data);
            let d = JSON.parse(data);
            let order = new Order(d["orderID"], d["user_id"], d["order_status"]);
            for(let i = 0; i< d["order_items"].legnth; i++){
                let _order_item = d['order_items'][i]
                let menu_item = d['menu_items'][i]
                let order_item = new Order_Item(menu_item['menu_item_id'], new Section(menu_item['section']['section_id'], menu_item['section']['section_name'], menu_item['section']['section_img']), menu_item['item_name'], menu_item['item_description'], menu_item['item_price'], menu_item['item_img'], menu_item['is_special'], _order_item["order_item_id"], _order_item["qty"])
                for(let y = 0; y < _order_item['ingredients'].length; y++){
                    let ingredient = _order_item['ingredients'][y];
                    order_item.addIngredient(new Ingredient(ingredient["ingredient_id"], ingredient["ingredient_name"], ingredient["ingredient_price"], ingredient["is_default"]));
                }
            }
            callback(order);
        })
    }
}

class Section{
    constructor(section_id, section_name, section_img){
        this.section_id = section_id;
        this.section_name = section_name;
        this.section_img = section_img;
    }


}

class Ingredient {
    constructor(ingredient_id, ingredient_name, ingredient_price, is_default){
        this.ingredient_id = ingredient_id;
        this.ingredient_name = ingredient_name;
        this.ingredient_price = parseFloat(ingredient_price);
        this.is_default = is_default;
    }
    static deleteIngredient(ingredient_id, menu_item_id){
        
        $.ajax({
            url : rootPath+"/as_capstone/includes/models/ajaxHandler.php",
            method : "POST",
            data:{
                'action' : 'deleteIngredient',
                'ingredient_id' : ingredient_id,
                'menu_item_id' : menu_item_id
            }
        })
        .fail(function(e) {console.log("An error occured on lucas.js")})
        .done(function(data){
            return(data);
        })
        
    }
}

