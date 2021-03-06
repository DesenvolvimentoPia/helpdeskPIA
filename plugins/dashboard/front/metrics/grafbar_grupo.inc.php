<?php

global $DB;

$query3 = "
SELECT count(glpi_groups_tickets.id) AS conta, glpi_groups.name AS name
FROM `glpi_groups_tickets`, glpi_tickets, glpi_groups
WHERE glpi_groups_tickets.`groups_id` = glpi_groups.id
AND glpi_groups_tickets.`tickets_id` = glpi_tickets.id
AND glpi_tickets.is_deleted = 0
$period
$entidade
GROUP BY name
ORDER BY conta DESC
LIMIT 5 ";

$result3 = $DB->query($query3) or die('erro');

$arr_grf3 = array();
while ($row_result = $DB->fetch_assoc($result3))
	{
	$v_row_result = $row_result['name'];
	$arr_grf3[$v_row_result] = $row_result['conta'];
	}

$grf3 = array_keys($arr_grf3) ;
$quant3 = array_values($arr_grf3) ;
$soma3 = array_sum($arr_grf3);

$grf_2 = implode("','",$grf3);
$grf_3 = "'$grf_2'";
$quant_2 = implode(',',$quant3);

echo "
<script type='text/javascript'>

$(function () {
        $('#grafsat').highcharts({
            chart: {
                type: 'bar',
                backgroundColor:'transparent',
                height:240                
            },
            title: {
                //text: 'Top 10 - ". __('Tickets','dashboard') ." ".__('by Group','dashboard')."'
                text:''
            },

            xAxis: {
                categories: [$grf_3],
                labels: {
                	  //rotation: -55,
                    align: 'right',
                    style: {
                    	font: '10pt Trebuchet MS, Verdana, sans-serif',                 
   	              	color: '#A0A0A0' 
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },

            },

             credits: {
   	         enabled: false
	   	     },
            plotOptions: {
                bar: {
                  pointPadding: 0.2,
                  borderWidth: 1,
                	borderColor: '#A0A0A0',
                	//shadow:true,
                	showInLegend: false
                }
            },
            series: [{
                //name: '".__('Tickets','dashboard')."',
                name:'',
                data: [$quant_2],
                dataLabels: {
                    enabled: true,                   
                    align: 'center',
                    x: 25,
                    y: 0,
                    color: '#A0A0A0',                     
                    style: {
                    	//font: 'Trebuchet MS, Verdana, sans-serif',
                    	fontSize: '13px',                 
   	              	fontWeight: 'bold',
   	              	textShadow: false
                    },
                    formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, '', ''); // Remove the thousands sep?
                }

                }
            }]
        });
    });

		</script>";

	   echo '</div>';

		?>
