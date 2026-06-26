if(layerList){
    if( typeof(layerList) == 'string'){

        var bannerList = eval("(" + layerList + ")");
    }else{
        var bannerList = layerList;
    }
}else{
    var bannerList =[];
}
