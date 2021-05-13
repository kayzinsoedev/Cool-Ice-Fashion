<div class="col-sm-12 milestones_box">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />
    <!-- Include jQuery Timeline CSS -->
    <link href="catalog/view/javascript/timeslider/dist/jquery.roadmap.min.css" rel="stylesheet" type="text/css" />
    <div id="my-timeline"></div>

    <!--Include jQuery Timeline Plugin--> 
    <script src="catalog/view/javascript/timeslider/dist/jquery.roadmap.js" type="text/javascript"></script>
    <?php 
        $data = array();
        $i = 0;
        foreach($milestones as $milestone){
            $data[$i]["date"] = $milestone['title'];
            $data[$i]["content"] = "<img src='image/".$milestone['image']."' class='img-responsive'>".html_entity_decode($milestone['description']);
            $i++;
        }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
        
            <?php if($data){ ?>
                var events = <?php echo json_encode($data); ?>;
            <?php }else{ ?>
                var events = array();
            <?php } ?>
                
            $('#my-timeline').roadmap(events, {
                eventsPerSlide: 6,
                slide: 1,
                prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
                nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
            });
		});
    </script>
<script type="text/javascript">

//  var _gaq = _gaq || [];
//  _gaq.push(['_setAccount', 'UA-36251023-1']);
//  _gaq.push(['_setDomainName', 'jqueryscript.net']);
//  _gaq.push(['_trackPageview']);
//
//  (function() {
//    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
//    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
//    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
//  })();

</script>
</div>