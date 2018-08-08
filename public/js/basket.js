var basket = [];
var response = {};
if (basket.length == 0) {
    document.getElementById('empty-sign').style.display = '';
    document.getElementById('shopping-cart').style.display = 'none';
    document.getElementById('sc-summ').style.display = 'none';
    document.getElementById('basket').style.display = 'none';
    document.getElementById('shop-section-cart-button').style.display = 'none';
}

function buyClick(id) {
    let itemcounter = document.getElementById('basket').getElementsByClassName('notification-counter')[0];
    let countId = "count" + id.toString();
    let count = document.getElementById(countId).value;
    let item = document.getElementById('shop-item-details' + id.toString());
    let itemname = item.getElementsByTagName('h1')[0].textContent;
    let itemcosttodisplay = item.getElementsByTagName('h2')[0].textContent;
    let itemcost = item.getElementsByTagName('h2')[1].textContent;
    let itemsum = itemcost * count;
    for (let i = 0; i < count; i++) {
        basket.push(id)
    }
    console.log(itemname + ' ' + itemcost + ' ' + count + ' ' + itemsum);
    let shoppingcart = document.getElementById('shopping-cart');
    shoppingcart.style.display = '';
    document.getElementById('empty-sign').style.display = 'none';
    document.getElementById('basket').style.display = '';
    document.getElementById('sc-summ').style.display = '';

    let itemrow = shoppingcart.insertRow();
    itemrow.insertCell().innerHTML = itemname;
    itemrow.insertCell().innerHTML = itemcosttodisplay;
    itemrow.insertCell().innerHTML = count;
    let sumcell = itemrow.insertCell();
    sumcell.innerHTML = itemsum.toString();

    document.getElementById('sc-summary-nd').innerText = (parseInt(document.getElementById('sc-summary-nd').innerText) + itemsum).toString();
    document.getElementById('sc-summary').innerText = parseInt(document.getElementById('sc-summary-nd').innerText).toLocaleString('ru-RU', {
        style: 'currency',
        currency: 'RUB'
    });

    itemcounter.style.display = '';
    itemcounter.innerText = (parseInt(itemcounter.innerText) + 1).toString();
    document.getElementById('shop-section-cart-button').style.display = '';
}