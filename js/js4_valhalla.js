let players = [];
let charInput;
let charactersUl;
let character_click_noticeP;
let chart;
let chartcontainerD;
let shareD;
let selected_players_query = "";
let show_top_x_s;
let hide_top_x_s;
let alltimeranking_d;

$(document).ready(function() {
	$.get('./scripts/get_valhalla.php?wat=players').then(function(resp) {
		players = JSON.parse(resp);
	});

	charInput = $('#character');
	charactersUl = $('#characters');
	character_click_noticeP =$('#character_click_notice');
	character_click_noticeP.hide();
	chartcontainerD = $('#chartcontainer');
	chartcontainerD.hide();
	shareD = $('#share');
	shareD.hide();
	show_top_x_s = $('#show_top_x_s');
	hide_top_x_s = $('#hide_top_x_s');
	hide_top_x_s.hide();
	alltimeranking_d = $('#alltimeranking');
	alltimeranking_d.css("height", "30vh");

	charInput.on("input", function(e) {
		charactersUl.html('');
		character_click_noticeP.show();
		let typed = this.value;
		if(empty(typed)) {
			return;
		}
		players.forEach(p => {
			if(p.name.toLowerCase().includes(typed.toLowerCase())) {
				let dewit = `getrlmps([{name:'${p.name.replace("'", "\\'")}',realm:${p.realm}}])`;
				charactersUl.append('<li class="clickable" onkeypress="'+dewit+'" onclick="'+dewit+'" tabindex="0"><span class ="realm'+ p.realm +'">'+p.name+'</span><span> ('+p.class+')</span></li>')
			}
		})
	});

	chart = new CanvasJS.Chart("chartcontainer", {
		data: [],
		backgroundColor:"rgba(0,0,0,0)",
		axisY: {
			title: 'realm points',
			titleFontColor: "rgb(0,75,141)",
			gridColor:"grey",
			gridDashType:"dot",
			gridThickness:1,
			labelFontSize:16,
			titleFontSize:20
		},
		axisX: {
			valueFormatString: "YYYY",
			gridColor:"grey",
			gridDashType:"dot",
			gridThickness:1,
			interlacedColor: "rgba(0,0,0,0.3)",
			interval:1,
			intervalType:"month",
			labelFormatter: function(e) {
				return e.value.getMonth()===0? e.value.getFullYear(): '';
			},
			labelAutoFit:false,
			labelFontSize:16
		},
		legend: {
			horizontalAlign:"center",
			verticalAlign:"top",
			fontFamily: 'Open Sans',
			fontColor:"white",
			fontSize:20
		},
		toolTip: {
			contentFormatter: function(e) {
				return `${e.entries[0].dataSeries.legendText}: ${new Date(e.entries[0].dataPoint.x).toUTCString()}: ${e.entries[0].dataPoint.y} krp`
			}
		}
	});
	chart.render();

	let wat = getQueryParameterByName('wat');
	if(!empty(wat)) {
		let whos = [];
		let whos_str = wat.split(",");
		whos_str.forEach(t => {
			if(!empty(t)) {
				let v = t.split(':');
				let name = v[0];
				let realm = v[1];
				if(!empty(name)) {
					whos.push({name: name, realm: realm})
				}
			}
		});
		getrlmps(whos);
	}
});

/** get rlmps from whos = obj(name,realm)[] */
function getrlmps(whos) {
	charactersUl.html('');
	let requests = [];
	whos.forEach(who => {
		requests.push($.get('./scripts/get_valhalla.php?wat=rlmp&name=' + who.name + '&realm=' + who.realm));
	});
	$.when.apply($, requests).then(function() {
		chartcontainerD.show();
		chartcontainerD.css('height', '80vh');
		$.each(arguments, function(i, resp) {
			// i liek javascript:
			try {
				resp = JSON.parse(resp);
			} catch(e) {
				try {
					resp = JSON.parse(resp[0]);
				} catch(e1) {
					console.warn("could not read response: "+resp)
					return true;
				}
			}
			let name = resp.name;
			let realm= resp.realm;
			let rlmps = resp.rlmps.map(row => {
				return {
					x: parseInt(row["time"] + "000"),
					y: row["rlmp"] === null ? null : parseInt(row["rlmp"])
				}
			});
			chart.options.data.push({
				type: "line",
				showInLegend: true,
				name: name,
				legendText: name,
				xValueType: "dateTime",
				dataPoints: rlmps,
				connectNullData: true,
				nullDataLineDashType: 'dot'
			});
			selected_players_query += name + ":" + realm+ ",";
		});
		chart.render();
		shareD.show();
		shareD.find('input').val('https://cor-forum.de/regnum/rankingarchive?wat=' + selected_players_query);
	});
}

function hide_top_x() {
	hide_top_x_s.hide();
	show_top_x_s.show();
	alltimeranking_d.css("height", "30vh");
}
function show_top_x() {
	hide_top_x_s.show();
	show_top_x_s.hide();
	alltimeranking_d.css("height", "100%");
}
