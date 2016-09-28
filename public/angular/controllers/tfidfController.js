sentimentAnalysis.controller('tfidfController', function($scope, $http)
{
    $scope.sortType     = 'id'; // set the default sort type
    $scope.sortReverse  = false;  // set the default sort order
    $scope.search   = '';     // set the default search/filter term

    $http(
    {
        url     : host + "/dashboard/idf/getTFIDF",
        method  : "POST"
    })
    .success(function(data)
    {
        $scope.datas = data;
        console.log($scope.datas);
    })
    .error(function (error)
    {
        console.log(error);
    });

});
