<?php
    defined('EMONCMS_EXEC') or die('Restricted access');
    global $path, $session, $v;
?>
<link href="<?php echo $path; ?>Modules/app/Views/css/config.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="<?php echo $path; ?>Modules/app/Views/css/dark.css?v=<?php echo $v; ?>" rel="stylesheet">
<style>
/* mobile version offset is default */
.chart-placeholder {
    --height-offset: 24rem;
}

/*
--------------
    adjust the full height offset for other specific devices
    based on width,height,orientation or dpi 
    @see: list of popular devices... https://css-tricks.com/snippets/css/media-queries-for-standard-devices/
-----------------
*/

/* ----------- bootstrap break points ----------- */
/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) {
    .chart-placeholder { --height-offset: 25rem; }
}
/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) {
    .chart-placeholder { --height-offset: 26rem; }
}
/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) {
    .chart-placeholder { --height-offset: 27rem; }
}
/* DEVICE SPECIFIC: */
/* ----------- Galaxy Tab 2 ----------- */
/* Portrait and Landscape */
@media (min-device-width: 800px) 
  and (max-device-width: 1280px) {
    .chart-placeholder {
        --height-offset: 27rem;
    }
}


/* set chart height to full screen height (100vh) minus an offset to cover the large value indicators and menus */
.chart-placeholder > * {
    height: calc(100vh - var(--height-offset))!important;
    min-height:180px;
}
</style>
<script type="text/javascript" src="<?php echo $path; ?>Modules/app/Lib/appconf.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/feed/feed.js?v=<?php echo $v; ?>"></script>

<script type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.min.js?v=<?php echo $v; ?>"></script> 
<script type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.time.min.js?v=<?php echo $v; ?>"></script> 
<script type="text/javascript" src="<?php echo $path; ?>Lib/flot/jquery.flot.selection.min.js?v=<?php echo $v; ?>"></script> 
<script type="text/javascript" src="<?php echo $path; ?>Lib/flot/date.format.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/vis.helper.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/app/Lib/timeseries.js?v=<?php echo $v; ?>"></script> 

<nav id="buttons" class="d-flex justify-content-between">
    <?php include(dirname(__DIR__).'/config-nav.php'); ?>
</nav>


<section id="app-block" style="display:none" class="block">
    <div class="d-flex justify-content-between">
        <div>
            <h5 class="electric-title mb-0 text-md-larger text-light" ><?php echo _('MAIN LEVEL') ?></h5>
            <h2 class="power-value display-sm-4 display-md-3 display-lg-2 mt-0 mb-0 text-primary"><span id="mainlevel" class="usenow">-</span></h2>
			<h6 class="d-xs mt-0" style="color:#0699fa">DAMPER &nbsp<span id="damper1_percent">-</span>%</h6>

        </div>
        <div class="text-xs-center">
            <h5 class="electric-title mb-0 text-md-larger text-light px-1"><span id="furnace_or_AC">-</span></h5>
            <h2 class="power-value display-sm-4 display-md-3 display-lg-2 mt-0 mb-0"><span id="HVAC_on">-</span></h2>
			<h6 class="d-xs mt-0" style="text-align:center;color:#f2f2f2">OUTSIDE &nbsp<span id="outside_temp">-</span></h6>

        </div>
        <div class="text-xs-right">
            <h5 class="electric-title mb-0 text-md-larger text-light"><?php echo _('UPSTAIRS') ?></h5>
            <h2 class="power-value display-sm-4 display-md-3 display-lg-2 mt-0 mb-0 text-warning"><span id="upstairs" class="solarnow">-</span></h2>
			<h6 class="d-xs mt-0" style="color:#dccc1f">DAMPER &nbsp<span id="damper2_percent">-</span>%</h6>
        </div>
    </div>

    <?php include(dirname(__DIR__).'/graph-nav.php'); ?>

	<div id="placeholder_bound" class="chart-placeholder">
		<div id="placeholder"></div>
	</div>

	<div class="d-flex justify-content-between">
		<div>
			<h5 class="text-md-larger text-lg-larger mb-0 text-primary"><span id="min_temp_main"></span> &nbsp <span class="electric-title text-light">MIN</span></h5>
			<h5 class="text-md-larger text-lg-larger mt-0 mb-0 text-primary"><span id="max_temp_main"></span> &nbsp <span class="electric-title text-light">MAX</span></h5>
			<h5 class="text-md-larger text-lg-larger mt-0 text-primary"><span id="average_temp_main"></span> &nbsp <span class="electric-title text-light">AVG</span></h5>
		</div>
		<div class="text-xs-center">
			<h5 class="electric-title text-md-larger text-lg-larger mb-0 text-light" style="padding-top:0px"><span id="total_heat_or_AC"></span></h5>
			<h5 class="text-md-larger text-lg-larger mb-0 mt-0" style="color:#71B28C"><span id="HVACtime"></h5>
			<!--<div class="smallertext" style="color:#f2f2f2">MAX 32</div>-->
			<!--<div class="smallertext" style="color:#f2f2f2">MIN 15</div>-->
		</div>
		<div class="text-xs-right">
			<h5 class="text-md-larger text-lg-larger mb-0 text-warning"><span class="electric-title text-light">MIN</span> &nbsp <span id="min_temp_up"></span></h5>
			<h5 class="text-md-larger text-lg-larger mb-0 mt-0 text-warning"><span class="electric-title text-light">MAX</span> &nbsp <span id="max_temp_up"></span></h5>
			<h5 class="text-md-larger text-lg-larger mt-0 text-warning"><span class="electric-title text-light">AVG</span> &nbsp <span id="average_temp_up"></h5>
		</div>
	</div>
</section>



<section id="app-setup" class="hide pb-3">
    <!-- instructions and settings -->
    <div class="px-3">
        <div class="row-fluid">
            <div class="span9 xapp-config-description">
                <div class="xapp-config-description-inner text-light">
                    <h2 class="app-config-title text-warning"><?php echo _('My Solar'); ?></h2>
                    <p class="lead">The My Solar app can be used to explore onsite solar generation, self consumption, export and building consumption both in realtime with a moving power graph view and historically with a daily and monthly bargraph.</p>
                    <p><strong class="text-white">Auto configure:</strong> This app can auto-configure connecting to emoncms feeds with the names shown on the right, alternatively feeds can be selected by clicking on the edit button.</p>
                    <p><strong class="text-white">Cumulative kWh</strong> feeds can be generated from power feeds with the power_to_kwh input processor.</p>
                    <img src="../Modules/app/images/mysolar_app.png" class="d-none d-sm-inline-block">
                </div>
            </div>
            <div class="span3 app-config pt-3"></div>
        </div>
    </div>
</section>

<div class="ajax-loader"></div>
<script src="<?php echo $path; ?>Lib/misc/gettext.js?v=<?php echo $v; ?>"></script> 

<script>
// ----------------------------------------------------------------------
// Globals
// ----------------------------------------------------------------------
// The feed api library requires the emoncms path
var path = "<?php print $path; ?>";
var apikey = "<?php print $apikey; ?>";
var sessionwrite = <?php echo $session['write']; ?>;

apikeystr = "";
if (apikey!="") apikeystr = "&apikey="+apikey;

feed.apikey = apikey;



// ----------------------------------------------------------------------
// Display
// ----------------------------------------------------------------------
$("body").css('background-color','#222');
$(window).ready(function(){
    $("#footer").css('background-color','#181818');
    $("#footer").css('color','#999');
});
if (!sessionwrite) $(".openconfig").hide();
//console.log("Sessionwrite: ", sessionwrite);

// ----------------------------------------------------------------------
// Configuration
// ----------------------------------------------------------------------
config.app = {
	"main":{"type":"feed", "autoname":"main", "engine":"5", "description":"Main Level Temperature Feed"},
    "upstairs":{"type":"feed", "autoname":"upstairs", "engine":"5", "description":"Upstairs Temperature Feed"},
	"furnace":{"type":"feed", "autoname":"furnace", "engine":"5", "description":"Furnace On/Off Feed"},
	"damper1":{"type":"feed", "autoname":"damper1", "engine":"5", "description":"Main Level Zone Damper Feed"},
	"damper2":{"type":"feed", "autoname":"damper12", "engine":"5", "description":"Upstairs Zone Damper Feed"},	
	"outside":{"type":"feed", "autoname":"outside", "engine":"5", "description":"Outside Temperature Feed"},
	"AC":{"type":"feed", "autoname":"ACfeed", "engine":"5", "description":"Air Conditioning Power Feed"}
};
config.name = "<?php echo $name; ?>";
config.db = <?php echo json_encode($config); ?>;
config.feeds = feed.list();

config.initapp = function(){init()};
config.showapp = function(){show()};
config.hideapp = function(){clear()};

// ----------------------------------------------------------------------
// APPLICATION
// ----------------------------------------------------------------------
<!--Brandon: declare global app variables, like lastupdate, here-->

var tempfeeds = []
var allAppFeeds = [];
var ACfeed; 
var shortestSeriesLength; 

var reload = true;
var autoupdate = true;
var lastupdate = 0;

config.init();

function init()
{
	var timeWindow = (3600000*6.0*1);
	view.end = +new Date;
	view.start = view.end - timeWindow;

	var placeholder = $('#placeholder');

	tempfeeds = [config.app.main.value, config.app.upstairs.value, config.app.furnace.value, config.app.damper1.value, config.app.damper2.value, config.app.outside.value, config.app.AC.value];
	allAppFeeds = [config.app.main.value, config.app.upstairs.value];
	ACfeed = config.app.AC.value;
  
	$("#zoomout").click(function () {view.zoomout(); reload = true; autoupdate = false; draw();});
	$("#zoomin").click(function () {view.zoomin(); reload = true; autoupdate = false; draw();});
	$('#right').click(function () {view.panright(); reload = true; autoupdate = false; draw();});
	$('#left').click(function () {view.panleft(); reload = true; autoupdate = false; draw();});
	$('.time').click(function () {
		view.timewindow($(this).attr("time")/24.0); 
		reload = true; 
		autoupdate = true;
		draw();
	});
        
	 //Brandon: this is *the* critical function to get the app to size the graph correctly when viewed on an iPhone in portrait mode.
	$(function() {
		$(document).on('window.resized hidden.sidebar.collapse shown.sidebar.collapse', resize)
	})
					 
	placeholder.bind("plotselected", function (event, ranges) {
		view.start = ranges.xaxis.from;
		view.end = ranges.xaxis.to;

		autoupdate = false;
		reload = true; 
		
		var now = +new Date();
		if (Math.abs(view.end-now)<30000) {
			autoupdate = true;
		}
		draw();        
	});

	$(window).resize(function(){
		resize();
		draw();
	});
	
}

function livefn() 
	{
    // Check if the updater ran in the last 60s if it did not the app was sleeping
    // and so the data needs a full reload.
    var now = +new Date();
	if ((now-lastupdate)>60000) reload = true;
    lastupdate = now;

  
    // Get latest feed values from the server (this returns the equivalent of what you see on the feed/list page)
    var feeds = feed.listbyid();

    for (var i in tempfeeds) {
		var feedid = tempfeeds[i];
		if (feeds[feedid]!=undefined) {
			if (autoupdate) {
				//console.log("Feed ",feedid," -- Length before: ", timeseries.length(tempfeeds[i]));
				timeseries.append(feedid,feeds[feedid].time,parseFloat(feeds[feedid].value));
				//console.log("Feed ",feedid," -- Length before trim: ", timeseries.length(tempfeeds[i]));
				timeseries.trim_start(feedid,view.start*0.001);
				//console.log("Feed ",feedid," -- Length after: ", timeseries.length(tempfeeds[i]));
				//The series will be different lengths after a while, so we need to find the shortest one so when we loop we don't loop too far
				if (timeseries.length(tempfeeds[i]) < shortestSeriesLength) shortestSeriesLength = timeseries.length(tempfeeds[i]);
				//console.log("Shortest Series Length: ", shortestSeriesLength);
			}
		}
    }	
	
	if (feeds[config.app.main.value]!=undefined) {
		var main_level_temp = parseFloat(feeds[config.app.main.value].value);
		$("#mainlevel").html(main_level_temp.toFixed(1));
	}

	if (feeds[config.app.upstairs.value]!=undefined) {
		var up_level_temp = parseFloat(feeds[config.app.upstairs.value].value);
		$("#upstairs").html(up_level_temp.toFixed(1));
	}
	
	if (feeds[config.app.outside.value]!=undefined) {
		var outside_temp = parseFloat(feeds[config.app.outside.value].value);
		$("#outside_temp").html(outside_temp.toFixed(0));
	}

	// AC feed
	if (feeds[ACfeed]!=undefined) {
		var use_now = parseFloat(feeds[ACfeed].value);
		$("#use_now").html(use_now.toFixed(0));
	}    

    var damper1_value = parseInt(feeds[1].value);
    var damper2_value = parseInt(feeds[2].value);
    var damper1_percent = (damper1_value - 56) / 17;
    var damper2_percent = (damper2_value - 56) / 17;
    damper1_display = (damper1_percent * 100).toFixed(); 
    damper2_display = (damper2_percent * 100).toFixed(); 

    $("#damper1_percent").html(damper1_display);
    $("#damper2_percent").html(damper2_display);
      
    var furnacecolor = "#71B28C";
    //Set colors for furnace status based on damper value and gradient from yellow to blue
	if (damper1_value!=null && damper1_value!==undefined) {
		if ((damper2_value==null || damper2_value==undefined) || damper2_value<58) {
			if (damper1_value<58) {furnacecolor = "#AAA";}
			else {furnacecolor = "#2691d9";}
		}
		else if (damper2_value < 64.5) {
			if (damper1_value<58) furnacecolor = "#d4c625";
			else if (furnacecolor < 64.5) {furnacecolor = "#71B28C";}
			else {furnacecolor = "#3BA5C3";}
		}
		else {
			if (damper1_value<58) {furnacecolor = "#d4c625";}
			else if (damper1_value < 64.5) {furnacecolor = "#A6BF55";}
			else {furnacecolor = "#71B28C";}
		}
	}
	$("#furnacecolor").html(furnacecolor);

	if (feeds[config.app.furnace.value]!=undefined) {
		var furnace_status = parseInt(feeds[config.app.furnace.value].value);
		console.log("Furnace value: ", furnace_status);
	}
	
        

	console.log("AC use_now:", use_now);
	$("#furnace_or_AC").html("HVAC");
    if (furnace_status == 73) {
        $("#furnace_or_AC").html("FURNACE");
        HVAC_on = "ON";
    } else if (use_now > 2000) {
        $("#furnace_or_AC").html("AC");
        HVAC_on = "ON";
    } else if (use_now > 900){
        $("#furnace_or_AC").html("FAN");
        HVAC_on = "ON";
    } else {
        HVAC_on = "OFF";
    }
        
	$("#HVAC_on").html("<span style='color:"+furnacecolor+"'>"+HVAC_on+"</span>");
	
    // Advance view
    if (autoupdate) {
        var timerange = view.end - view.start;
        view.end = now;
        view.start = view.end - timerange;
    }
        
    //console.log("vs-ve: "+view.start+" "+view.end);
    if (autoupdate && $('#placeholder_bound').width() > 0) draw();
	}

function show()
	{	
	app_log("INFO","myHVAC show");
	$(".ajax-loader").hide();
	
	resize();

	livefn();
    
    // reload data at interval
    live = setInterval(livefn,5000);
	}

function clear()
	{
    clearInterval(live);
	}

function resize() 
	{
	var top_offset = 0;
	var placeholder_bound = $('#placeholder_bound');
	var placeholder = $('#placeholder');

	var width = placeholder_bound.width();
	var height = $(window).height()*0.55;
	console.log("Height: "+height+"  Width: "+width);

	if (height>width) height = width;
 
        //placeholder.width(width);
        //placeholder_bound.height(height);
        //placeholder.height(height-top_offset);

	if($('#app-block').is(":visible")) {
		draw();
    }
    }
  
function draw()
	{
	var dp = 1;
	var units = "C";
	var fill = false;
	var plotColour = 0;
	
	var options = {
		lines: { fill: fill },
		xaxis: { mode: "time", timezone: "browser", min: view.start, max: view.end},
		yaxes: [{min: 55},{position: "right"}],
		grid: {hoverable: true, clickable: true, markings: []},
		selection: { mode: "x" }
	}
	
	var npoints = 1500;
	interval = Math.round(((view.end - view.start)/npoints)/1000);
	if (interval<1) interval = 1;

	var npoints = parseInt((view.end-view.start)/(interval*1000));



    // -------------------------------------------------------------------------------------------------------
    // LOAD DATA ON INIT OR RELOAD
    // -------------------------------------------------------------------------------------------------------
	if (reload) {
		reload = false;
		view.start = 1000*Math.floor((view.start/1000)/interval)*interval;
		view.end = 1000*Math.ceil((view.end/1000)/interval)*interval;
		
		//The series will be different lengths after a while, so we need to find the shortest one so when we loop we don't loop too far
		shortestSeriesLength = npoints*2; //A starting point that will be longer than all the series lengths. We will find the minimum lenghts as we load.
		
		for (var i in tempfeeds) {
			var feedid = tempfeeds[i];
			timeseries.load(feedid,feed.getdata(feedid,view.start,view.end,interval));
			if (timeseries.length(tempfeeds[i]) < shortestSeriesLength) shortestSeriesLength = timeseries.length(tempfeeds[i]); 
			//console.log("Shortest Series Length: ", shortestSeriesLength);
		}
	}
	
	
	// -------------------------------------------------------------------------------------------------------

    var main_temp_data = [];
	var up_temp_data = [];
	var furnace_data = [];

	var t = 0;

    var maintemp = 0;
    var uptemp = 0;
  
      
    var furnaceval = 0;
	var damper1val = 0; 	
	var damper2val = 0; 	
	
	var use_data = [];
	var use = 0;
	var total_use_kwh = 0;


    //initialize variables for accumulating here: main
    var maintemptotal = 0;
	var maintempavg = 0;
	var maintempmin = null;
	var maintempmax = 0;
	var main_non_null_pts = 0;

    //initialize variables for accumulating here: upstairs
    var uptemptotal = 0;
	var uptempavg = 0;
	var uptempmin = null;
	var uptempmax = 0;
	var up_non_null_pts = 0;

	//init accumulators for furnace ranges
	var furnacestartpoint = null;
	var furnacestoppoint = null;
    var furnaceseconds = 0;


	//init accumulators for damper markings
	var damper1startpoint = null;
	var damper2startpoint = null;
	var damper1stoppoint = null;
	var demper2stoppoint = null;	


	var datastart = timeseries.start_time(tempfeeds[0]);  //OEM uses the solar feed start_time. I just use the first feed in my array of feeds.
	 
	var furnid = config.app.furnace.value;
	var damp1id = config.app.damper1.value;
	var damp2id = config.app.damper2.value;
	var dampercolor = null;
	var currentcolor = null;
	var colorchange = false;
	


	//Loop through the data store to create series data, set max, min values, and create markings
	for (var z=0; z<shortestSeriesLength-1; z++) {
		
		// -------------------------------------------------------------------------------------------------------
		// Get AC use values
		// -------------------------------------------------------------------------------------------------------
		use = 0;
		var feedid = ACfeed;
		if (timeseries.value(feedid,z)!=null) {
			use = timeseries.value(feedid,z);
			if (use < 40) use = 0; //get rid of low-level noise and negative values
		}
		
		
		// -------------------------------------------------------------------------------------------------------
		// Get temperature values
		// -------------------------------------------------------------------------------------------------------

		var feedid = config.app.main.value;
		if (timeseries.value(feedid,z)) {
			maintemp = timeseries.value(feedid,z);
			if (main_non_null_pts==0) {
				//now set earlier datapoints to the first non-null one
				for (var j=0; j<z; j++) {
				main_temp_data[j][1]= maintemp;
				}
			}
			main_non_null_pts += 1;
			maintemptotal += maintemp;
		if (maintempmin == null) maintempmin = maintemp
		else if (maintemp < maintempmin) maintempmin = maintemp;
		if (maintempmax == 0) maintempmax = maintemp
		else if (maintemp > maintempmax) maintempmax = maintemp;
		}

		var feedid = config.app.upstairs.value;
		if (timeseries.value(feedid,z)) {
			uptemp = timeseries.value(feedid,z);
			if (up_non_null_pts==0) {
				//now set earlier datapoints to the first non-null one
				for (var j=0; j<z; j++) {
				up_temp_data[j][1]= uptemp;
				}
			}			
			up_non_null_pts += 1;
			uptemptotal += uptemp;
		if (uptempmin == null) uptempmin = uptemp
		else if (uptemp < uptempmin) uptempmin = uptemp;
		if (uptempmax == 0) uptempmax = uptemp
		else if (uptemp > uptempmax) uptempmax = uptemp;
		}

		// -------------------------------------------------------------------------------------------------------
		// Get furnace values
		// -------------------------------------------------------------------------------------------------------

		//Brandon: also handle the case where the furnaceval is going to return a lot of nulls because the feed was down
		//damper1val = null;  //reset dampervals because if they are returning null we want to get the gray #AAA so we know
		//damper2val = null;
		
		if (timeseries.value(furnid,z)) {
			furnaceval = timeseries.value(furnid,z);
		}
		if (timeseries.value(damp1id,z)) {
			damper1val = timeseries.value(damp1id,z);
		}
		if (timeseries.value(damp2id,z)) {
			damper2val = timeseries.value(damp2id,z);
		}

		//Set colors for dampers based on gradient from yellow to blue
		if (damper2val<58 || damper2val==null) {
			if (damper1val<58 || damper1val==null) {dampercolor = "#AAA";}
				else {dampercolor = "#2691d9";}
				}
			else if (damper2val < 64.5) {
				if (damper1val<58) dampercolor = "#d4c625";
				else if (dampercolor < 64.5) {dampercolor = "#71B28C";}
				else {dampercolor = "#3BA5C3";}
				}
			else {
				if (damper1val<58) {dampercolor = "#d4c625";}
				else if (damper1val < 64.5) {dampercolor = "#A6BF55";}
				else {dampercolor = "#71B28C";}
				}
		
		if (dampercolor!=currentcolor) {
			colorchange = true;
			currentcolor = dampercolor;
		}

		//start a timer on the furnace
		if (furnaceval==73) if (furnacestartpoint == null) {
				furnacestartpoint = datastart + (1000 * interval * z);
		currentcolor = dampercolor
//		    console.log("Furnace Start "+furnacestartpoint);
				}
	
		//stop the timer on the furnace
		if (furnacestartpoint!=null && (colorchange || (furnaceval==56 || z==(npoints-1)))) {
			furnacestoppoint = datastart + (1000 * interval * z);
			options.grid.markings.push({ xaxis: { from: furnacestartpoint, to: furnacestoppoint }, color: dampercolor, opacity: 0.2});


			furnaceseconds += (furnacestoppoint - furnacestartpoint) / 1000;
			
			furnacestartpoint = null;

			if (colorchange && furnaceval==73) {
					furnacestartpoint = furnacestoppoint;
			} 
		}

		total_use_kwh += (use*interval)/(1000*3600);

		var time = datastart + (1000 * interval * z);
		main_temp_data.push([time,maintemp]);
		up_temp_data.push([time,uptemp]); 
		use_data.push([time,use]);			

		t += interval;

	}

	maintempavg = maintemptotal / main_non_null_pts;
	$("#average_temp_main").html(maintempavg.toFixed(1));
	$("#min_temp_main").html(maintempmin.toFixed(1));
	$("#max_temp_main").html(maintempmax.toFixed(1));

	uptempavg = uptemptotal / up_non_null_pts;
	$("#average_temp_up").html(uptempavg.toFixed(1));
	$("#min_temp_up").html(uptempmin.toFixed(1));
	$("#max_temp_up").html(uptempmax.toFixed(1));
	
	display_use_kwh = Math.round(total_use_kwh*10) / 10;
	
    //Brandon: decide what to do with totals for kWH on the fan and AC, but for now, just total the furnace
	//if (furnaceseconds!=undefined) {
	//	if (furnaceseconds==0) {
	//		$("#total_heat_or_AC").html("TOTAL AC");
	//	} else if (total_use_kwh==0) {
	//		$("#total_heat_or_AC").html("TOTAL HEATING");
	//	} else {
	//		$("#total_heat_or_AC").html("TOTAL HVAC");
	//		$("#extraHVACtime").html("<span style='color:"+currentcolor+"'>"+display_use_kwh.toFixed(1)+" kWh</span>");
	//	}
	//} else {
	//	$("#total_heat_or_AC").html("TOTAL HVAC")
	//}
	
    if (furnaceseconds!=undefined) {
      $("#total_heat_or_AC").html("TOTAL HEATING");
    }
      //	} else {
      //		$("#total_heat_or_AC").html("TOTAL HVAC");
      //		$("#extraHVACtime").html("<span style='color:"+currentcolor+"'>"+display_use_kwh.toFixed(1)+" kWh</span>");
      //	}
      //} else {

    var furnacetimedisplay = "TOTAL ";
    var furnaceminutes = null;
    var furnacehours = null;
    if (furnaceseconds < 3600) {
        furnaceminutes = Math.floor(furnaceseconds / 60);
        if (furnaceminutes < 10) {
            furnaceseconds -=  (furnaceminutes * 60);
            furnacetimedisplay = furnaceminutes.toFixed(0) + " min "+furnaceseconds.toFixed(0)+" sec";
        } else {
            furnacetimedisplay = furnaceminutes.toFixed(0) + " min ";
        }
    } else {
        furnacehours = Math.floor(furnaceseconds / 3600);
        furnaceseconds -=  (furnacehours * 3600);
        furnaceminutes = Math.floor(furnaceseconds / 60);
        furnacetimedisplay = furnacehours.toFixed(0) + " hr "+furnaceminutes.toFixed(0) +" min";
    }
	
    //Brandon: decide what to do with totals for kWH on the fan and AC, but for now, just total the furnace
	//if (total_use_kwh==0) {
	//	$("#HVACtime").html("<span style='color:"+currentcolor+"'>"+furnacetimedisplay+"</span>");
	//} else {
	//	$("#HVACtime").html("<span style='color:"+currentcolor+"'>"+display_use_kwh.toFixed(1)+" kWh</span>");
	//}
	
    $("#HVACtime").html("<span style='color:"+currentcolor+"'>"+furnacetimedisplay+"</span>");
	
        options.xaxis.min = view.start;
        options.xaxis.max = view.end;
        var mintemp = Math.min(maintempmin,uptempmin);
        options.yaxes[0].min = mintemp - 5;

        var series = [
        {data:main_temp_data,color: "#0699fa",lines:{lineWidth:1}},
	    {data:up_temp_data, color: "#dccc1f",lines:{lineWidth:1}},
        {data:use_data,yaxis: 2, color: "#0699fa",lines:{lineWidth:0, fill:0.8}}
        ];
	

	$.plot($('#placeholder'),series,options);
	}
    
	//Brandon: this is *the* critical function to get the app to size the graph correctly when viewed on an iPhone in portrait mode.
$(function() {
    $(document).on('window.resized hidden.sidebar.collapse shown.sidebar.collapse', function() {
		console.log("Resizing because side bar collapsed");
		resize();
	})
	})

/*
//Brandon: I'm not sure how the mySolarpv app does this without a listener, but I found I had to add a listener to reload the graph when the config closes, in case the feed changed.
$(function(){
    // listen to the config.closed event before resizing the graph
    $('body').on('config.closed', function() {
		alert("Config closed.");
		reload = true;
		init();
    })
	})
*/

// ----------------------------------------------------------------------
// App log
// ----------------------------------------------------------------------
function app_log (level, message) {
    if (level=="ERROR") alert(level+": "+message);
    console.log(level+": "+message);
	}
</script>

