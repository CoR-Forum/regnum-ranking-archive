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
	$.get('get.php?wat=players').then(function(resp) {
		players = JSON.parse(resp);
	});

	charInput = $('#character');
	charactersUl = $('#characters');
	character_click_noticeP =$('#character_click_notice');
	character_click_noticeP.hide();
	chartcontainerD = $('#chartcontainer');
	//chartcontainerD.hide();
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

	const ctx = document.getElementById('chart').getContext('2d');
	chart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: [ new Date("2008-08-01").toISOString(), new Date().toISOString() ],
			datasets: []
		},
		options: {
			scales: {
				xAxes: [{
					type: 'time'
				}],
				yAxes: [{
					label: 'krp'
				}]
			}
		}
		/*,
		options: {
			scales: {
				yAxes: [{
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
					}]
			}
		} */
	});

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
async function getrlmps(whos) {
	charactersUl.html('');
	const datas = await Promise.all(whos.map(async (who) => {
		const response = await fetch(`get.php?wat=rlmp&name=${who.name}&realm=${who.realm}`)
		const json = await response.json()
		return json
	}));
	chartcontainerD.show()
	for(const resp of datas) {
		let name = resp.name;
		let realm= resp.realm;
		let rlmps = resp.rlmps.map(row => ({
			t: new Date(parseInt(row["time"] + "000")).toISOString(),
			y: row["rlmp"] === null ? null : parseInt(row["rlmp"])
		}))
		chart.data.datasets.push({
			label: name,
			data: rlmps,
			backgroundColor: 'red',
			fill: false
		})
		selected_players_query += name + ":" + realm+ ",";
	}
	chart.update()
	shareD.show();
	shareD.find('input').val('https://cor-forum.de/regnum/rankingarchive?wat=' + selected_players_query);
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
