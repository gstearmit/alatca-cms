var f = jQuery.noConflict();

f(document).ready(function(){

f('#target').mosaicSlider(

        // funke
        // {
        //     columnWidth : 480,
        //     rows : 3,
        //     width : 'auto',
        //     height : 730,
        //     bypass: false,
        //     photoMosaic : {
        //         padding: 8,
        //         columns:4,
        //         order : 'columns'
        //     },
        //     prettyPhoto : {
        //         theme : 'dark_rounded'
        //     },
        //     callbacks : {
        //         at_right : function(){
        //             // $('#loading').show();
        //             // $('#contentarea').load('mosaic.php?page=1');
        //         }
        //     }
        // }


        // makfak
        {
            columnWidth : 240,
            rows : 2,
   
            height : '480',
            photoMosaic : {
            	padding: 20,
                order : 'masonry',
         	
                lazyload : {
                    active : true,
                    threshold : 100
                }
            },
            prettyPhoto : {
                theme : 'dark_rounded'
            },
            
            callbacks : {
            
            
                at_left : function(){
        
                    console.log('left');
                }
            }
        }

        );
        
});
