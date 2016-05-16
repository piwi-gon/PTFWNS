<?php
include("sessionStart.php");
$jquery   = $base->getExtension("cJQuery");
$debMsg   = "tried to load external class jquery";
// first test check class loading with information
if(is_object($jquery)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                   { $base->deb($debMsg." -> failed", "CLS", 2); }

$jqueryUI = $base->getExtension("cJQueryUI");
$debMsg   = "tried to load external class jqueryui";
// first test check class loading with information
if(is_object($jqueryUI)) { $base->deb($debMsg." -> OK", "CLS", 2);     }
else                     { $base->deb($debMsg." -> failed", "CLS", 2); }

?>
<!DOCTYPE html>
<html>
<head>
<title>Bar Charts</title>
<?php
echo $jquery->getScriptTag("/lib/js");
echo $jqueryUI->getCSSTag("/css");
echo $jqueryUI->getScriptTag("/lib/js");
?>
</head>
<body>
<div>You Clicked: <span id="infoMonthId">Nothing yet</span></div>
<div id="chart8" style="margin-top:20px; margin-left:20px; width:800px; height:560px;"></div>
<script type="text/javascript">
$(document).ready(function(){
    var s1 = new Array(); // [2, -6, 7, -5];
    var ticks = new Array(); //['a', 'b', 'c', 'd'];
    $.getJSON("data/jqplotData.php?selectedMonth=month", function(data) {
        $.each(data, function(key, value) {
            s1.push(value);
            ticks.push(key);
        });
        plot7 = $.jqplot('chart8', [s1], {
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                rendererOptions: { fillToZero: true },
                    pointLabels: { show: true }
            },
            axes: {
                // yaxis: { autoscale: true },
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            }
        });
    });
    $("#chart8").bind('jqplotDataClick', function(ev, seriesIndex, pointIndex, data) {
        $('#infoMonthId').html("series: " + seriesIndex + ", point: " + pointIndex + ", data: " + data);
    });
});
</script>
<script class="include" type="text/javascript" src="lib/js/jqplot/jquery.jqplot.js"></script>
<script class="include" type="text/javascript" src="lib/js/jqplot/plugins/jqplot.barRenderer.min.js"></script>
<script class="include" type="text/javascript" src="lib/js/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script class="include" type="text/javascript" src="lib/js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script class="include" type="text/javascript" src="lib/js/jqplot/plugins/jqplot.pointLabels.min.js"></script>
</body>
</html>
