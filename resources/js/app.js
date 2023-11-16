import "./bootstrap";
import { createApp } from "vue";

const app = createApp({});

app.config.globalProperties.translate = {
    action : 'Action',
    actionViewOrder : 'View Order',
    actionViewProduct : 'Details Product',
    add : 'Add',
    addProduct : 'Add product',
    back : 'Back', 
    category : 'Category',
    cart : 'Go to cart',
    cartPage : 'Cart Page',
    checkout : 'Checkout',
    checkoutInformation : 'Checkout Information',
    comments : 'Comments',
    contactDetails : 'Contact Details',
    customerDetails : 'Customer Details',
    date : 'Date',
    delete : 'Delete',
    description : 'Description',
    edit : 'Edit',
    email : 'Email',
    emailError : 'The email was not sent successfully!',
    emailSucces : 'The email was sent successfully!',
    emptyCart : 'The cart is empty!',
    error : 'An error has occurred. Please try again.',
    id : 'Id',
    image : 'Image',
    insufficientStock : 'The stock is insufficient.',
    index : 'Index Page',
    invalid : 'Invalid credentials!',
    login : 'Login',
    logout : 'Logout',
    name : 'Name',
    nameShop : 'Shop Online',
    notOrders : 'No orders found!',
    notProductsInDB : 'Not products in database',
    order : 'Order',
    ordersPage : 'Orders',
    password : 'Password',
    price : 'Price',
    productsPage : 'Products Page',
    productPage : 'Product',
    productsRecommended : 'Products Recommended',
    purchasedProducts : 'Purchased Products',
    remove : 'Remove',
    setQuantity : 'Set quantity',
    save : 'save',
    seeOrder : 'See the order',
    submitOrder : 'Thank you so much for your order! Below are the products you ordered!',
    title : 'Title',
    totalPrice : 'Total Price',
    update : 'Update',
    userName : 'Username',
    yourQuantity : 'Quantity'
};

import IndexComponent from "./route.vue";
app.component("index-component", IndexComponent);

app.mount(".index");

