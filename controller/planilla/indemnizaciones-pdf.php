<?php
ob_start();
require('../fpdf185/fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('header.jpg',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Move to the right
    //$this->Cell(80);
    // Title
    $this->Cell(0,5,convertTexto('CARTA DE LIQUIDACIÓN LABORAL'),0,2,'C',false);
    //$this->Cell(0,5,convertTexto('IBD115-D CONSULTORES, S.A. DE C.V.'),0,2,'C',false);
    //$this->Cell(0,5,convertTexto('COLONIA ESCALON, AVENIDA LA CAPILLA, NUMERO 321, SAN SALVADOR'),0,2,'C', false);
    //$this->Cell(0,5,convertTexto('TELÉFONO: (+503) 2202 - 5555'),0,2,'C', false);
    // Line break
    $this->Ln(10);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

function convertTexto($text) {
    return $text = iconv('UTF-8', 'ISO-8859-1', $text);
}

// Connect to database
$conn = mysqli_connect('localhost', 'root', '', 'rrhh');

// Select data from table
$query = "SELECT id_empleado, nombres, apellidos, cargo, fecha_ingreso, salario, (select depto.nombre from departamentos depto where depto.id_depto = emp.id_depto) area,
                    fecha_salida, tipo_salida, estado_indem, estado,
                    CONVERT(datetime, '12/12/2021', 103) fec_calc_ant,
                    CONVERT(datetime, '12/12/2022', 103) fec_calc_act,
                    DATEDIFF(day, CONVERT(datetime, '12/12/2022', 103), fecha_ingreso) AS dias_desde_ing,
                    DATEDIFF(day, CONVERT(datetime, '12/12/2022', 103), CONVERT(datetime, '12/12/2021', 103)) AS dias_desde_calc_ant
                    FROM empleados emp where estado in ('I')";

if (isset($_GET['id'])) {
    $query .= " and id_empleado = " . $_GET['id'];
}

$result = mysqli_query($conn, $query);

// Initialize PDF object
$pdf = new PDF();
$pdf->AliasNbPages();

// Output data from table
while($fila = mysqli_fetch_array($result)) {
    $pdf->AddPage();

    // Set font and color for text
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,convertTexto('IBD115-D CONSULTORES, S.A. DE C.V.'),0,2,'L',false);
    $pdf->Cell(0,5,convertTexto('COLONIA ESCALON, AVENIDA LA CAPILLA, NUMERO 321, SAN SALVADOR'),0,2,'L', false);
    $pdf->Cell(0,5,convertTexto('TELÉFONO: (+503) 2202 - 5555'),0,2,'L', false);
    $pdf->Ln(10);
    //$pdf->SetTextColor(0,0,255);

    date_default_timezone_set('America/El_Salvador');
    setlocale(LC_TIME, 'es_SV');
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $pdf->Cell(0,5,convertTexto('San Salvador, ' . $dias[date('w')] . ', ' . date('j') . ' de ' . $meses[date('n')-1] . ' de ' . date('Y')),0,2,'R', false);
    $pdf->Ln(10);

    $pdf->Cell(0,5,convertTexto("Departamento de Talento Humano"),0,2,'L', false);
    $pdf->Ln(10);

    /*$pdf->Cell(45,5,convertTexto('Período de pago:'));
    $pdf->Cell(40,5,convertTexto(date("d/m/Y", strtotime("last day of this month"))));
    $pdf->Cell(45,5,convertTexto('Banco'));
    $pdf->Cell(40,5,convertTexto('Banco Promérica de El Salvador'));
    $pdf->Ln();

    $pdf->Cell(45,5,convertTexto('Número de cuenta:'));
    $pdf->Cell(40,5,convertTexto('1' . rand(pow(10, 14), pow(10, 15) - 1)));
    $pdf->Cell(45,5,convertTexto('Concepto'));
    $pdf->Cell(40,5,convertTexto('Salario'));
    $pdf->Ln();

    $pdf->Cell(45,5,'Nombre:');
    $pdf->Cell(40,5,convertTexto($fila['nombres'] . ' ' . $fila['apellidos']));
    $pdf->Cell(45,5,'Proyecto');
    $pdf->Cell(40,5,'General Electric');
    $pdf->Ln(20);

    

    // Set font and color for text
    $pdf->SetFont('Arial','B',10);

    $pdf->Cell(85,5,'INGRESOS');
    $pdf->Cell(85,5,'DEDUCCIONES');
    $pdf->Ln(10);*/

    // Set font and color for text
    $pdf->SetFont('Arial','',10);

        $fechaCalculo = getdate();

        $dias_desde_ing = $fila["dias_desde_ing"];
        $dias_desde_calc_ant = $fila["dias_desde_calc_ant"];

        $date1 = $fila["fecha_ingreso"];
        $date2 = date("Y-m-d");

        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $mesesEnEmpresa = (($year2 - $year1) * 12) + ($month2 - $month1);
        $mesesEnEmpresa = 12 - $month2 + $mesesEnEmpresa;

        /*$baseCalculoIndem = round($fila["salario"], 2);
        $baseCalculoIndemDiario = round($baseCalculoIndem/30, 2);

        if ($baseCalculoIndem > (365 * 4)) {
            $baseCalculoIndem = 365 * 4;
        }

        if ($fila["estado"] == 'R' && $baseCalculoIndem > (365 * 2)) {
            $baseCalculoIndem = 365 * 2;
        }

        $indemnizacion = round($baseCalculoIndem * ($year2 - $year1 - 1), 2);

        //$indemnizacion = round($baseCalculoIndem * ($mesesEnEmpresa/12),2);

        $indemnizacion = $indemnizacion + round($baseCalculoIndemDiario * 30 * $month2 / 360, 2);

        $indemnizacion = $indemnizacion + round($baseCalculoIndemDiario * 30 * (12 - $month1) / 360, 2);

        $diasCalculoIndem = date('d', strtotime('today'));

        if ($diasCalculoIndem > 30) {
            $diasCalculoIndem = 30;
        }

        $indemnizacion = $indemnizacion + round($baseCalculoIndemDiario * $diasCalculoIndem / 360,2);*/

        $salario_anual = round($fila["salario"], 2);
        $fecha_ingreso = new DateTime($fila["fecha_ingreso"]);
        $fecha_salida = new DateTime($fila["fecha_salida"]);

        $anios_trabajados = $fecha_ingreso->diff($fecha_salida)->y;
        $meses_trabajados = $fecha_ingreso->diff($fecha_salida)->m;
        $dias_trabajados = $fecha_ingreso->diff($fecha_salida)->d;

        $salario_anios_trabajados = $anios_trabajados * $salario_anual;

        $salario_diario = $salario_anual / 365;
        $salario_meses_trabajados = ($meses_trabajados * $salario_diario * cal_days_in_month(CAL_GREGORIAN, $fecha_salida->format('m'), $fecha_salida->format('Y')));
        $salario_dias_trabajados = ($dias_trabajados * $salario_diario);

        $indemnizacion_total = $salario_anios_trabajados + $salario_meses_trabajados + $salario_dias_trabajados;
        $indemnizacion = $indemnizacion_total;

        if ($meses_trabajados < 12) {
            $dias_vacaciones_proporcionales = round((15 / 365) * ($meses_trabajados * 30 + $dias_trabajados));
        } else {
            $dias_vacaciones_proporcionales = 15;
        }
        
        $monto_vacaciones_proporcionales = round(($dias_vacaciones_proporcionales / 15) * ($salario_anual / 2) * 0.3);
        $vacaciones = $monto_vacaciones_proporcionales;

        //La diferencia puede deberse a la forma en que se calcula el monto proporcional a los meses y días restantes. En mi respuesta anterior, utilicé la función cal_days_in_month() para obtener el número de días en el mes correspondiente. Sin embargo, algunos cálculos en línea pueden utilizar una fracción fija de 30.44 días por mes1. Si utilizamos esta fracción en lugar de cal_days_in_month(), obtenemos una indemnización total de $6,173.33.

        //echo "La indemnización total es: " . number_format($indemnizacion_total, 2);

        $textoTiempoLaborado = "";

        if (($year2 - $year1) == 1) {
            $textoTiempoLaborado = "1 año ";
        }

        if (($year2 - $year1) > 1) {
            $textoTiempoLaborado = ($year2 - $year1) . " años ";
        }

        if ($mesesEnEmpresa % 12 > 0) {
            $textoTiempoLaborado .= ($mesesEnEmpresa % 12) . " meses ";
        }

        // = $textoTiempoLaborado;

        $salarioBase = round($fila["salario"], 2);

        // = "$".number_format($salarioBase,2);
        
        $montoAFP = round($salarioBase * 0.0725, 2);

        // = "$".number_format($montoAFP,2);

        $montoISSS = round($salarioBase * 0.03, 2);

        if ($montoISSS > 30) {
            $montoISSS = 30;
        }
        
        // = "$".number_format($montoISSS,2);

        

        $costoMensual = $salarioBase + $montoAFP + $montoISSS;

        /*if (12 - $month2 + ($mesesEnEmpresa % 12) >= 12) {
            $anhiosParaCalculo = $year2 - $year1 + 1;
        } else {
            $anhiosParaCalculo = $year2 - $year1;
        }*/
        

        if ($mesesEnEmpresa < 12) {
            $factor = 15 * ($mesesEnEmpresa / 12);
        } else if ($mesesEnEmpresa >= 12 && $mesesEnEmpresa <= 36) {
            $factor = 15;
        } else if ($mesesEnEmpresa >= 36 && $mesesEnEmpresa < 120) {
            $factor = 19;
        } else if ($mesesEnEmpresa > 120) {
            $factor = 21;
        }

        if ($dias_desde_ing < 365) {
            $aguinaldo = $salarioBase / 30 * 15 * $dias_desde_ing / 365;
        } else {
            $aguinaldo = $salarioBase / 30 * $factor;
        }

        $renta = 0;
        $salarioNeto = round($salarioBase - $montoAFP - $montoISSS, 2);

        if ($salarioNeto >= 0.01 && $salarioNeto <= 472) {
            $renta = 0;
        } else if ($salarioNeto >= 472.01 && $salarioNeto <= 895.24) {
            $renta = ($salarioNeto - 472) * 0.1 + 17.67;
        } else if ($salarioNeto >= 895.25 && $salarioNeto <= 2038.10) {
            $renta = ($salarioNeto - 895.24) * 0.2 + 60;
        } else if ($salarioNeto >= 2038.11) {
            $renta = ($salarioNeto - 2038.10) * 0.3 + 288.57;
        }
        
        // = "$".number_format(round($renta, 2),2);

         //= "$".number_format(round($aguinaldo, 2),2);

        

        //$vacaciones = round($salarioBase/30*15, 2);
        //$vacaciones = $vacaciones * 0.3;
        
        // = "$".number_format(round($vacaciones, 2),2);

        $salarioNeto = round($salarioNeto - $renta, 2);

        // = "$".number_format(round($salarioNeto, 2), 2);

        // = '<button class="btn btn-success btn-sm" onclick="return genBoleta('.$fila["id_empleado"].');"><i class="fa fa-file-pdf" aria-hidden="true"></i> Descargar</button>';

        //$totalPlanilla = $totalPlanilla + $costoMensual;

        //$datos[] = $sub_array;

        /*$pdf->Cell(45,5,'Sueldo Devengado:');
        $pdf->Cell(40,5,convertTexto("$".number_format(round($salarioNeto, 2),2)));

        $pdf->Cell(45,5,'ISSS:');
        $pdf->Cell(40,5,convertTexto("$".number_format($montoISSS,2)));
        $pdf->Ln();

        $pdf->Cell(45,5,'Vacaciones');
        $pdf->Cell(40,5,convertTexto(''));

        $pdf->Cell(45,5,'AFP:');
        $pdf->Cell(40,5,convertTexto("$".number_format($montoAFP,2)));
        $pdf->Ln();

        $pdf->Cell(45,5,'Bonificaciones Vacaciones:');
        $pdf->Cell(40,5,convertTexto(''));
 
        $pdf->Cell(45,5,'Renta:');
        $pdf->Cell(40,5,convertTexto("$".number_format($renta,2)));
        $pdf->Ln();

        $pdf->Cell(45,5,'Comisiones:');
        $pdf->Cell(40,5,convertTexto(''));

        $pdf->Cell(45,5,'');
        $pdf->Cell(40,5,convertTexto(''));
        $pdf->Ln();

        $pdf->Cell(45,5,'Horas Extra:');
        $pdf->Cell(40,5,convertTexto(''));

        $pdf->Cell(45,5,'Descuentos:');
        $pdf->Cell(40,5,convertTexto(''));
        $pdf->Ln();
        
        $pdf->Cell(45,5,'');
        $pdf->Cell(40,5,convertTexto(''));

        $pdf->Cell(45,5,convertTexto('Préstamos:'));
        $pdf->Cell(40,5,convertTexto(''));
        $pdf->Ln(20);
        
        $pdf->Cell(45,5,'TOTAL DE INGRESOS (A):');
        $pdf->Cell(40,5,convertTexto("$".number_format(round($salarioBase, 2), 2)));

        $pdf->Cell(45,5,'TOTAL DEDUCCIONES (B):');
        $pdf->Cell(40,5,convertTexto("$".number_format(round($salarioBase - $salarioNeto, 2), 2)));
        $pdf->Ln(10);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(170,5,convertTexto("LIQUIDO A RECIBIR (A-B): $".number_format(round($salarioNeto, 2), 2)), 0, 0, 'C');
        $pdf->Ln(20);*/

        // Set font and color for text
        $pdf->SetFont('Arial','',10);

        $pdf->MultiCell(0,5,convertTexto("La presente está dirigida a " . $fila['nombres'] . ' ' . $fila['apellidos'] . " quien hasta la presente fecha había desempeñado labores en el puesto de " . $fila['cargo'] 
        . " en el área de " . $fila['area'] . " durante " . $textoTiempoLaborado . "."),0,'J');
        $pdf->Ln(5);

        $totalPagarIndem = $salarioNeto + $vacaciones + $indemnizacion + $aguinaldo;

        $pdf->MultiCell(0,5,convertTexto("El motivo de esta carta está relacionado con el cálculo de la liquidación que le corresponde al empleado luego de"
            . " su " . (($fila["tipo_salida"] == "R") ? "renuncia voluntaria" : "despido") . ". La cantidad final, que recibirá es de " . "$" . number_format($totalPagarIndem, 2) .  ". Y a continuación se desglosa cada uno de los conceptos de la sumatoria anterior: "),0,'J');
        $pdf->Ln(5);

        $pdf->Cell(50,5,convertTexto('Salario:'), 0, 0, 'L');
        $pdf->Cell(50,5,convertTexto("$" . number_format($salarioNeto, 2)), 0, 0, 'R');
        $pdf->Ln();

        $pdf->Cell(50,5,convertTexto('Vacaciones:'), 0, 0, 'L');
        $pdf->Cell(50,5,convertTexto("$" . number_format($vacaciones, 2)), 0, 0, 'R');
        $pdf->Ln();

        $pdf->Cell(50,5,convertTexto('Indemnización:'), 0, 0, 'L');
        $pdf->Cell(50,5,convertTexto("$" . number_format($indemnizacion, 2)), 0, 0, 'R');
        $pdf->Ln();

        $pdf->Cell(50,5,convertTexto('Aguinaldo:'), 0, 0, 'L');
        $pdf->Cell(50,5,convertTexto("$" . number_format($aguinaldo, 2)), 0, 0, 'R');
        $pdf->Ln();

        $pdf->Ln(10);

        $pdf->MultiCell(0,5,convertTexto('Sepa usted que está en todo su derecho de realizar los cálculos pertinentes para verificar que la cantidad descrita en esta carta es la correcta. En caso de no serlo, puede presentar un reclamo y se hará el cálculo una vez más.'),0,'J');
        $pdf->Ln(5);

        $pdf->MultiCell(0,5,convertTexto('Y si usted está de acuerdo con las condiciones que estipula la presente carta, por favor firme en señal de aceptación. '),0,'J');
        $pdf->Ln(5);
        
        $pdf->Ln(20);

        // Set font and color for text
        $pdf->SetFont('Arial','B',10);

        $pdf->Cell(0,5,convertTexto('ACEPTACIÓN DEL EMPLEADO'));
        $pdf->Ln(10);

        // Set font and color for text
        $pdf->SetFont('Arial','',10);

        $pdf->Cell(15,5,convertTexto('F'), 0, 0, 'R');
        $pdf->Cell(70,5,convertTexto('________________________________'), 0, 0, 'L');
        $pdf->Cell(15,5,convertTexto('DUI'), 0, 0, 'R');
        $pdf->Cell(70,5,convertTexto('________________________________'), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(15,5,convertTexto(''));
        $pdf->Cell(60,5,convertTexto($fila['nombres'] . ' ' . $fila['apellidos']), 0, 0, 'C');
        $pdf->Ln(20);

        // Set font and color for text
        $pdf->SetFont('Arial','B',10);

        $pdf->Cell(0,5,convertTexto('ACEPTACIÓN DE LA EMPRESA'));
        $pdf->Ln(10);

        $pdf->SetFont('Arial','',10);
        
        $pdf->Cell(15,5,convertTexto('F'), 0, 0, 'R');
        $pdf->Cell(70,5,convertTexto('________________________________'), 0, 0, 'L');
        $pdf->Cell(15,5,convertTexto('Fecha: '), 0, 0, 'R');
        $pdf->Cell(70,5,convertTexto($dias[date('w')] . ', ' . date('j') . ' de ' . $meses[date('n')-1] . ' de ' . date('Y')), 0, 0, 'L');
        $pdf->Ln();

        $pdf->Cell(15,5,convertTexto(''));
        $pdf->Cell(60,5,convertTexto('Lic. Javier Hernández'), 0, 0, 'C');
        $pdf->Ln(20);

        $pdf->Ln();
}

// Output PDF file
$pdf->Output();
ob_end_flush();
?>