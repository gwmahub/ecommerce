'use strict';

app.directive('filterProduit', function () {
    return {
        templateUrl : baseView + 'Produits/_filterProduit.html',
        restrict: 'E'
    }
});