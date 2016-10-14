<?php


include('../isdk.php');

$contact_id = (isset($_POST['contact_id']) ? (int)$_POST['contact_id'] : 0 );
$prev_mo_pay = (isset($_POST['prev_mo_pay']) ? (double)preg_replace('/[^0-9.]/s', '', $_POST['prev_mo_pay']) : 0 );
$current_mo_pay = (isset($_POST['prev_mo_pay']) ? (double)preg_replace('/[^0-9.]/s', '', $_POST['prev_mo_pay']) : 0 );
$mo_increase = (isset($_POST['prev_mo_pay']) ? (double)preg_replace('/[^0-9.]/s', '', $_POST['prev_mo_pay']) : 0 );
$retro_mult = (isset($_POST['contact_id']) ? (int)$_POST['contact_id'] : 0 );
$date_claim_filed = (isset($_POST['date_claim_filed']) ? (DateTime)$_POST['date_claim_filed'] : 0 );
$date_claim_award = (isset($_POST['date_claim_award']) ? (DateTime)$_POST['date_claim_award'] : 0 );
$contract_mo_count = (isset($_POST['contact_id']) ? (int)$_POST['contact_id'] : 0 );
$debug_email = (isset($_POST['debug_email']) ? trim($_POST['debug_email']) : '');

if($contact_id === 0)
{
    echo 'No Contact ID provided.';
    return;
}

$infusionsoft = new iSDK();
$infusionsoft->cfgCon('tz184');

//Calculate Yearly Increase
$yearly_increase = (double)(12 * $mo_increase);

//Calculate Mo Increase Owed
$mo_increase_owed = (double)($contract_mo_count * $mo_increase);

//Calculate Months Retro Due
$retro_mo_count = ($date_claim_filed->diff($date_claim_award);

//Calculate Total Retro Due to Veteran
$retro_total = (double)($retro_mo_count * $mo_increase);

//Calculate Retro Owed
$retro_owed = (double)($retro_mult * $retro_total);

//Calculate Invoice Total
$invoice_total = (double)($mo_increase_owed + $retro_owed);

//Calculate Minimum Payment
if ($contract_mo_count < 5)
    $minumum_payment = (double)($invoice_total / $contract_mo_count);
else
    $minumum_payment = (double)($invoice_total / ($contract_mo_count * 2));

//Update all Fields in Infusionsoft
//$uYearlyIncrease = $infusionsoft->updateCon($contact_id , array('_TempCurrencyField' => $yearly_increase));
//$uRetroMoCount = $infusionsoft->updateCon($contact_id , array('_TempWholeNumberField' => $retro_mo_count));
//$uRetroTotal = $infusionsoft->updateCon($contact_id , array('_TempCurrencyField2' => $retro_total));
//$uRetroOwed = $infusionsoft->updateCon($contact_id , array('_TempCurrencyCalculationField' => $retro_owed);
//$uMoIncreaseOwed = $infusionsoft->updateCon($contact_id , array('_TempCurrencyField3' => $mo_increase_owed));
//$uInvoiceTotal = $infusionsoft->updateCon($contact_id , array('_ClaimInvoiceTotal' => $invoice_total));

if($debug_email !== '')
        mail($debug_email,'VCP Invoicing Testing - Debug Email',  print_r($GLOBALS, true));

?>