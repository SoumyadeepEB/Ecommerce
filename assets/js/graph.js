base_url = window.location.origin + '/ecommerce/'
if(window.location.href == base_url + 'ordersummary.php'){
    window.onload = function() {
        var date = new Date()

        var today_income = parseInt($('#today').text().split(' ')[1].replace(',',''))
        var yesterday_income = parseInt($('#yesterday').text().split(' ')[1].replace(',',''))
        var total_income = today_income + yesterday_income;
        var incomeData = {
            "Today vs Yesterday Income": [{
                cursor: "pointer",
                explodeOnClick: false,
                innerRadius: "75%",
                legendMarkerType: "square",
                name: "Today vs Yesterday Income",
                radius: "100%",
                showInLegend: true,
                startAngle: 90,
                type: "doughnut",
                dataPoints: [
                    {y: today_income, name: "Today's Income", color: "#E7823A"},
                    {y: yesterday_income, name: "Yesterday's Income", color: "#546BC1"}
                ]
            }],
        };

        var graph1 = {
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Today vs Yesterday Income"
            },
            legend: {
                fontFamily: "calibri",
                fontSize: 14,
                itemTextFormatter: function (e) {
                    return e.dataPoint.name + ": " + Math.round(e.dataPoint.y / total_income * 100) + "%";  
                }
            },
            data: []
        };
        graph1.data = incomeData["Today vs Yesterday Income"];
        $("#chartContainer1").CanvasJSChart(graph1);

        var day1 = parseInt($('#mon').text().split(' ')[1].replace(',',''))
        var day2 = parseInt($('#tue').text().split(' ')[1].replace(',',''))
        var day3 = parseInt($('#wed').text().split(' ')[1].replace(',',''))
        var day4 = parseInt($('#thu').text().split(' ')[1].replace(',',''))
        var day5 = parseInt($('#fri').text().split(' ')[1].replace(',',''))
        var day6 = parseInt($('#sat').text().split(' ')[1].replace(',',''))
        var day7 = parseInt($('#sun').text().split(' ')[1].replace(',',''))
        var graph2 = {
            animationEnabled: true,
            title: {
                text: "Weekly Report"
            },
            axisY: {
                title: "Income in INR",
            },
            axisX: {
                title: "Day"
            },
            data: [{
                type: "column",
                yValueFormatString: "#,##0.0#"%"",
                dataPoints: [
                    {label: "Monday", y: day1},	
                    {label: "Tuesday", y: day2},	
                    {label: "Wednesday", y: day3},
                    {label: "Thursday", y: day4},	
                    {label: "Friday", y: day5},
                    {label: "Saturday", y: day6},
                    {label: "Sunday", y: day7},
                    
                ]
            }]
        };
        $("#chartContainer2").CanvasJSChart(graph2);

        var adjustedDate = date.getDate() + date.getDay()
        var prefixes = ['0','1','2','3','4','5']
        var week = parseInt(prefixes[0 | adjustedDate / 7]) + 1
        json = []
        switch(week){
            case 1:
                var w1 = parseInt($('#week1').text().split(' ')[1].replace(',',''))
                json = [
                    {label: "Week 1", y: w1}
                ]
                break
            case 2:
                var w1 = parseInt($('#week1').text().split(' ')[1].replace(',',''))
                var w2 = parseInt($('#week2').text().split(' ')[1].replace(',',''))
                json = [
                    {label: "Week 1", y: w1},
                    {label: "Week 2", y: w2}
                ]
                break
            
            case 3:
                var w1 = parseInt($('#week1').text().split(' ')[1].replace(',',''))
                var w2 = parseInt($('#week2').text().split(' ')[1].replace(',',''))
                var w3 = parseInt($('#week3').text().split(' ')[1].replace(',',''))
                json = [
                    {label: "Week 1", y: w1},
                    {label: "Week 2", y: w2},
                    {label: "Week 3", y: w3}
                ]
                break
            case 4:
                var w1 = parseInt($('#week1').text().split(' ')[1].replace(',',''))
                var w2 = parseInt($('#week2').text().split(' ')[1].replace(',',''))
                var w3 = parseInt($('#week3').text().split(' ')[1].replace(',',''))
                var w4 = parseInt($('#week4').text().split(' ')[1].replace(',',''))
                json = [
                    {label: "Week 1", y: w1},
                    {label: "Week 2", y: w2},
                    {label: "Week 3", y: w3},
                    {label: "Week 4", y: w4}
                ]
                break
            case 5:
                var w1 = parseInt($('#week1').text().split(' ')[1].replace(',',''))
                var w2 = parseInt($('#week2').text().split(' ')[1].replace(',',''))
                var w3 = parseInt($('#week3').text().split(' ')[1].replace(',',''))
                var w4 = parseInt($('#week4').text().split(' ')[1].replace(',',''))
                var w5 = parseInt($('#week5').text().split(' ')[1].replace(',',''))
                json = [
                    {label: "Week 1", y: w1},
                    {label: "Week 2", y: w2},
                    {label: "Week 3", y: w3},
                    {label: "Week 4", y: w4},
                    {label: "Week 5", y: w5}
                ]
                break
        }
        var graph3 = {
            animationEnabled: true,
            title: {
                text: "Monthly Report"
            },
            axisX: {
                labelFontSize: 15,
                title: 'Week'
            },
            axisY: {
                labelFontSize: 15,
                title: "Income in INR",
            },
            data: [{
                type: "spline",
                dataPoints: json
            }]
        };
        $("#chartContainer3").CanvasJSChart(graph3);

        var month1 = parseInt($('#jan').text().split(' ')[1].replace(',',''))
        var month2 = parseInt($('#feb').text().split(' ')[1].replace(',',''))
        var month3 = parseInt($('#mar').text().split(' ')[1].replace(',',''))
        var month4 = parseInt($('#apr').text().split(' ')[1].replace(',',''))
        var month5 = parseInt($('#may').text().split(' ')[1].replace(',',''))
        var month6 = parseInt($('#jun').text().split(' ')[1].replace(',',''))
        var month7 = parseInt($('#jul').text().split(' ')[1].replace(',',''))
        var month8 = parseInt($('#aug').text().split(' ')[1].replace(',',''))
        var month9 = parseInt($('#sept').text().split(' ')[1].replace(',',''))
        var month10 = parseInt($('#oct').text().split(' ')[1].replace(',',''))
        var month11 = parseInt($('#nov').text().split(' ')[1].replace(',',''))
        var month12 = parseInt($('#dec').text().split(' ')[1].replace(',',''))
        var graph4 = {
            animationEnabled: true,
            title: {
                text: "Yearly Report"
            },
            axisY: {
                title: "Income in INR",
            },
            axisX: {
                title: "Month"
            },
            data: [{
                type: "column",
                yValueFormatString: "#,##0.0#"%"",
                dataPoints: [
                    {label: "January", y: month1},	
                    {label: "February", y: month2},	
                    {label: "March", y: month3},
                    {label: "April", y: month4},	
                    {label: "May", y: month5},
                    {label: "June", y: month6},
                    {label: "July", y: month7},
                    {label: "August", y: month8},
                    {label: "September", y: month9},	
                    {label: "October", y: month10},
                    {label: "November", y: month11},
                    {label: "December", y: month12},
                ]
            }]
        };
        $("#chartContainer4").CanvasJSChart(graph4);
    }
}