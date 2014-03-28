var Cart = new function (){
    this.total = 0;
    this.updateProduct = function (id) {
        var productTotal = 0;
        var qty = parseInt($('#qty_'+id).val());
        var price = $('#price_'+id).text();
        var oldProductTotal = parseFloat($('#product_total_'+id).text());

        if(isInt(qty) && qty > 0){
            productTotal = parseFloat(price) * qty;
        }else{
            $('#qty_'+id).val(0);
            return;
        }
        this.total = this.total - (oldProductTotal?oldProductTotal:0)+ productTotal;
        $('#qty_'+id).val(qty);
        $('#product_total_'+id).text(productTotal.toFixed(2));
    };
    this.update = function () {
        this.total = 0;
        _self = this;
        $('.product_quantity').each(function(){
            var id = $(this).prop('id').replace('qty_','');
            _self.updateProduct(id);
        });

        $('#total').text(this.total.toFixed(2));
    }
}


function isInt(n) {
   return typeof n === 'number' && n % 1 == 0;
}

Cart.update();

$('.product_quantity').keyup(function(e){
    var id = $(this).prop('id').replace('qty_','');
    Cart.updateProduct(id);
    $('#total').text(Cart.total.toFixed(2));
});