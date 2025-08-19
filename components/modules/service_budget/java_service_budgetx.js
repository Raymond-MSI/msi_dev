var iprice=0, ibpd=0, i_out_price=0;
var i_out_price_0=0,i_out_price_1=0,i_out_price_2=0,i_out_price_3=0,i_out_price_4=0;
var i_tbrandusd1=0, i_tbrandusd2=0, i_tbrandusd3=0, i_tbrandusd4=0, i_tbrandusd5=0, i_tbrandusd=0, itotalbrand=0;
var m_tbrandusd1=0, m_tbrandusd2=0, m_tbrandusd3=0, m_tbrandusd4=0, m_tbrandusd5=0, m_tbrandusd=0, mtotalbrand=0;
var m_addon_price_0=0, m_addon_price_1=0, m_addon_price_2=0, m_addon_price_3=0, m_addon_price_4=0, addon_price=0;
var m_out_price_0=0, m_out_price_1=0, m_out_price_2=0, m_out_price_3=0, m_out_price_4=0, m_out_price=0;
var mprice=0, mbpd_price=0, mout_price=0;
var mandays11=0, mandays21=0, mandays12=0, mandays22=0, mandays13=0, mandays23=0, mandays14=0, mandays24=0, mandays15=0, mandays25=0;
// var wprice=0;
var w_mandays11=0, w_mandays21=0, w_mandays12=0, w_mandays22=0, w_mandays13=0, w_mandays23=0, w_mandays14=0, w_mandays24=0, w_mandays15=0, w_mandays25=0;
var error_backup_unit='', error_solution='';
var sproduct=0, sservice=0;
var i_agreed=0, m_agreed=0, agreed=0;
var i_totalbrand1=0, i_totalbrand2=0, i_totalbrand3=0, i_totalbrand4=0, i_totalbrand5=0;

// Service Budget Type
function sbtypex() {
    if(document.getElementById('sbtype1').checked) {
        solution_service_enabled();
        document.getElementById('bundling_project').style.display='';
        // document.getElementById('bundling_backup').style.display='';
        // document.getElementById('agreed_price').style.display='';
        agreed_price_enabled();
        document.getElementById('multiyearsx').style.display='';
        document.getElementById('multiyearsy').style.display='';
        // document.getElementById('multiyears0').checked='checked';
        // document.getElementById('i_bundling').checked='checked';
        change_bundling_i();
        change_bundling_m();
        change_bundling_w();
    } else {
        // document.getElementById('implementation-tab').style.display='none';
        // document.getElementById('maintenance-tab').style.display='none';
        // document.getElementById('warranty-tab').style.display='none';
        implementation_tab_disabled();
        maintenance_tab_disabled();
        warranty_tab_disabled();
        // document.getElementById('labelservice').style.display='none';
        // document.getElementById('DCCIS').style.display='none';
        // document.getElementById('ECS').style.display='none';
        // document.getElementById('BDAS').style.display='none';
        // document.getElementById('DBMS').style.display='none';
        // document.getElementById('ASAS').style.display='none';
        // document.getElementById('SPS').style.display='none';
        // document.getElementById('s_total_service').style.display='none';
        solution_service_disabled();
        document.getElementById('bundling_project').style.display='none';
        // document.getElementById('bundling_backup').style.display='none';
        // document.getElementById('agreed_price').style.display='none';
        agreed_price_disabled();
        document.getElementById('multiyearsx').style.display='none';
        document.getElementById('multiyearsy').style.display='none';
    }
    change_multiyears();
}

// Formula Implementation
function i_change_price() {
    iprice = document.getElementById('i_price').value;
    document.getElementById('i_price').value=format(iprice);
    document.getElementById('i_price_copy').value=format(iprice);
    i_totalbranded();
}

function i_change_bpd() {
    ibpd = document.getElementById('i_bpd_price').value;
    document.getElementById('i_bpd_price').value=format(ibpd);
    i_totalbranded();
}

// function i_change_out() {
//     iout = document.getElementById('i_out_price').value;
//     document.getElementById('i_out_price').value=format(iout);
//     i_totalbranded();
// }

function i_changebrand1() {
    var man11 = document.getElementById('i_brand1_PD').value;
    var mandays11 = document.getElementById('i_brand1_PDM').value;
    var catalog11 = document.getElementById('i_total_PD').value;
    var totalusd11 = man11*mandays11*catalog11;
    var man21 = document.getElementById('i_brand1_PM').value;
    var mandays21 = document.getElementById('i_brand1_PMM').value;
    var catalog21 = document.getElementById('i_total_PM').value;
    var totalusd21 = man21*mandays21*catalog21;
    var man31 = document.getElementById('i_brand1_PC').value;
    var mandays31 = document.getElementById('i_brand1_PCM').value;
    var catalog31 = document.getElementById('i_total_PC').value;
    var totalusd31 = man31*mandays31*catalog31;
    var man41 = document.getElementById('i_brand1_PA').value;
    var mandays41 = document.getElementById('i_brand1_PAM').value;
    var catalog41 = document.getElementById('i_total_PA').value;
    var totalusd41 = man41*mandays41*catalog41;
    var man51 = document.getElementById('i_brand1_EE').value;
    var mandays51 = document.getElementById('i_brand1_EEM').value;
    var catalog51 = document.getElementById('i_total_EE').value;
    var totalusd51 = man51*mandays51*catalog51;
    var man61 = document.getElementById('i_brand1_EP').value;
    var mandays61 = document.getElementById('i_brand1_EPM').value;
    var catalog61 = document.getElementById('i_total_EP').value;
    var totalusd61 = man61*mandays61*catalog61;
    var man71 = document.getElementById('i_brand1_EA').value;
    var mandays71 = document.getElementById('i_brand1_EAM').value;
    var catalog71 = document.getElementById('i_total_EA').value;
    var totalusd71 = man71*mandays71*catalog71;

    i_tbrandusd1 = totalusd11+totalusd21+totalusd31+totalusd41+totalusd51+totalusd61+totalusd71;
    tbrandusd = i_tbrandusd1+i_tbrandusd2+i_tbrandusd3+i_tbrandusd4+i_tbrandusd5;

    if(tbrandusd>0) {
        var totalidr1 = totalusd11/tbrandusd*itotalbrand+totalusd21/tbrandusd*itotalbrand+totalusd31/tbrandusd*itotalbrand+totalusd41/tbrandusd*itotalbrand+totalusd51/tbrandusd*itotalbrand+totalusd61/tbrandusd*itotalbrand+totalusd71/tbrandusd*itotalbrand;
    } else {
        totalidr1=0;
    }

    i_totalbrand1=totalidr1;
    document.getElementById('i_totalbrand1').value=format(totalidr1) ; 
}

function i_changebrand2() {
    var man12 = document.getElementById('i_brand2_PD').value;
    var mandays12 = document.getElementById('i_brand2_PDM').value;
    var catalog12 = document.getElementById('i_total_PD').value;
    var totalusd12 = man12*mandays12*catalog12;
    var man22 = document.getElementById('i_brand2_PM').value;
    var mandays22 = document.getElementById('i_brand2_PMM').value;
    var catalog22 = document.getElementById('i_total_PM').value;
    var totalusd22 = man22*mandays22*catalog22;
    var man32 = document.getElementById('i_brand2_PC').value;
    var mandays32 = document.getElementById('i_brand2_PCM').value;
    var catalog32 = document.getElementById('i_total_PC').value;
    var totalusd32 = man32*mandays32*catalog32;
    var man42 = document.getElementById('i_brand2_PA').value;
    var mandays42 = document.getElementById('i_brand2_PAM').value;
    var catalog42 = document.getElementById('i_total_PA').value;
    var totalusd42 = man42*mandays42*catalog42;
    var man52 = document.getElementById('i_brand2_EE').value;
    var mandays52 = document.getElementById('i_brand2_EEM').value;
    var catalog52 = document.getElementById('i_total_EE').value;
    var totalusd52 = man52*mandays52*catalog52;
    var man62 = document.getElementById('i_brand2_EP').value;
    var mandays62 = document.getElementById('i_brand2_EPM').value;
    var catalog62 = document.getElementById('i_total_EP').value;
    var totalusd62 = man62*mandays62*catalog62;
    var man72 = document.getElementById('i_brand2_EA').value;
    var mandays72 = document.getElementById('i_brand2_EAM').value;
    var catalog72 = document.getElementById('i_total_EA').value;
    var totalusd72 = man72*mandays72*catalog72;

    i_tbrandusd2 = totalusd12+totalusd22+totalusd32+totalusd42+totalusd52+totalusd62+totalusd72;
    tbrandusd = i_tbrandusd1+i_tbrandusd2+i_tbrandusd3+i_tbrandusd4+i_tbrandusd5;

    if(tbrandusd>0) {
        var totalidr2 = totalusd12/tbrandusd*itotalbrand+totalusd22/tbrandusd*itotalbrand+totalusd32/tbrandusd*itotalbrand+totalusd42/tbrandusd*itotalbrand+totalusd52/tbrandusd*itotalbrand+totalusd62/tbrandusd*itotalbrand+totalusd72/tbrandusd*itotalbrand;
    } else {
        totalidr2=0;
    }

    i_totalbrand2=totalidr2;
    document.getElementById('i_totalbrand2').value=format(totalidr2) ; 
}

function i_changebrand3() {
    var man13 = document.getElementById('i_brand3_PD').value;
    var mandays13 = document.getElementById('i_brand3_PDM').value;
    var catalog13 = document.getElementById('i_total_PD').value;
    var totalusd13 = man13*mandays13*catalog13;
    var man23 = document.getElementById('i_brand3_PM').value;
    var mandays23 = document.getElementById('i_brand3_PMM').value;
    var catalog23 = document.getElementById('i_total_PM').value;
    var totalusd23 = man23*mandays23*catalog23;
    var man33 = document.getElementById('i_brand3_PC').value;
    var mandays33 = document.getElementById('i_brand3_PCM').value;
    var catalog33 = document.getElementById('i_total_PC').value;
    var totalusd33 = man33*mandays33*catalog33;
    var man43 = document.getElementById('i_brand3_PA').value;
    var mandays43 = document.getElementById('i_brand3_PAM').value;
    var catalog43 = document.getElementById('i_total_PA').value;
    var totalusd43 = man43*mandays43*catalog43;
    var man53 = document.getElementById('i_brand3_EE').value;
    var mandays53 = document.getElementById('i_brand3_EEM').value;
    var catalog53 = document.getElementById('i_total_EE').value;
    var totalusd53 = man53*mandays53*catalog53;
    var man63 = document.getElementById('i_brand3_EP').value;
    var mandays63 = document.getElementById('i_brand3_EPM').value;
    var catalog63 = document.getElementById('i_total_EP').value;
    var totalusd63 = man63*mandays63*catalog63;
    var man73 = document.getElementById('i_brand3_EA').value;
    var mandays73 = document.getElementById('i_brand3_EAM').value;
    var catalog73 = document.getElementById('i_total_EA').value;
    var totalusd73 = man73*mandays73*catalog73;

    i_tbrandusd3 = totalusd13+totalusd23+totalusd33+totalusd43+totalusd53+totalusd63+totalusd73;
    tbrandusd = i_tbrandusd1+i_tbrandusd2+i_tbrandusd3+i_tbrandusd4+i_tbrandusd5;

    if(tbrandusd>0) {
        var totalidr3 = totalusd13/tbrandusd*itotalbrand+totalusd23/tbrandusd*itotalbrand+totalusd33/tbrandusd*itotalbrand+totalusd43/tbrandusd*itotalbrand+totalusd53/tbrandusd*itotalbrand+totalusd63/tbrandusd*itotalbrand+totalusd73/tbrandusd*itotalbrand;
    } else {
        totalidr3=0;
    }

    i_totalbrand3=totalidr3;
    document.getElementById('i_totalbrand3').value=format(totalidr3) ; 
}

function i_changebrand4() {
    var man14 = document.getElementById('i_brand4_PD').value;
    var mandays14 = document.getElementById('i_brand4_PDM').value;
    var catalog14 = document.getElementById('i_total_PD').value;
    var totalusd14 = man14*mandays14*catalog14;
    var man24 = document.getElementById('i_brand4_PM').value;
    var mandays24 = document.getElementById('i_brand4_PMM').value;
    var catalog24 = document.getElementById('i_total_PM').value;
    var totalusd24 = man24*mandays24*catalog24;
    var man34 = document.getElementById('i_brand4_PC').value;
    var mandays34 = document.getElementById('i_brand4_PCM').value;
    var catalog34 = document.getElementById('i_total_PC').value;
    var totalusd34 = man34*mandays34*catalog34;
    var man44 = document.getElementById('i_brand4_PA').value;
    var mandays44 = document.getElementById('i_brand4_PAM').value;
    var catalog44 = document.getElementById('i_total_PA').value;
    var totalusd44 = man44*mandays44*catalog44;
    var man54 = document.getElementById('i_brand4_EE').value;
    var mandays54 = document.getElementById('i_brand4_EEM').value;
    var catalog54 = document.getElementById('i_total_EE').value;
    var totalusd54 = man54*mandays54*catalog54;
    var man64 = document.getElementById('i_brand4_EP').value;
    var mandays64 = document.getElementById('i_brand4_EPM').value;
    var catalog64 = document.getElementById('i_total_EP').value;
    var totalusd64 = man64*mandays64*catalog64;
    var man74 = document.getElementById('i_brand4_EA').value;
    var mandays74 = document.getElementById('i_brand4_EAM').value;
    var catalog74 = document.getElementById('i_total_EA').value;
    var totalusd74 = man74*mandays74*catalog74;

    i_tbrandusd4 = totalusd14+totalusd24+totalusd34+totalusd44+totalusd54+totalusd64+totalusd74;
    tbrandusd = i_tbrandusd1+i_tbrandusd2+i_tbrandusd3+i_tbrandusd4+i_tbrandusd5;

    if(tbrandusd>0) {
        var totalidr4 = totalusd14/tbrandusd*itotalbrand+totalusd24/tbrandusd*itotalbrand+totalusd34/tbrandusd*itotalbrand+totalusd44/tbrandusd*itotalbrand+totalusd54/tbrandusd*itotalbrand+totalusd64/tbrandusd*itotalbrand+totalusd74/tbrandusd*itotalbrand;
    } else {
        totalidr4=0;
    }

    i_totalbrand4=totalidr4;
    document.getElementById('i_totalbrand4').value=format(totalidr4) ; 
}

function i_changebrand5() {
    var man15 = document.getElementById('i_brand5_PD').value;
    var mandays15 = document.getElementById('i_brand5_PDM').value;
    var catalog15 = document.getElementById('i_total_PD').value;
    var totalusd15 = man15*mandays15*catalog15;
    var man25 = document.getElementById('i_brand5_PM').value;
    var mandays25 = document.getElementById('i_brand5_PMM').value;
    var catalog25 = document.getElementById('i_total_PM').value;
    var totalusd25 = man25*mandays25*catalog25;
    var man35 = document.getElementById('i_brand5_PC').value;
    var mandays35 = document.getElementById('i_brand5_PCM').value;
    var catalog35 = document.getElementById('i_total_PC').value;
    var totalusd35 = man35*mandays35*catalog35;
    var man45 = document.getElementById('i_brand5_PA').value;
    var mandays45 = document.getElementById('i_brand5_PAM').value;
    var catalog45 = document.getElementById('i_total_PA').value;
    var totalusd45 = man45*mandays45*catalog45;
    var man55 = document.getElementById('i_brand5_EE').value;
    var mandays55 = document.getElementById('i_brand5_EEM').value;
    var catalog55 = document.getElementById('i_total_EE').value;
    var totalusd55 = man55*mandays55*catalog55;
    var man65 = document.getElementById('i_brand5_EP').value;
    var mandays65 = document.getElementById('i_brand5_EPM').value;
    var catalog65 = document.getElementById('i_total_EP').value;
    var totalusd65 = man65*mandays65*catalog65;
    var man75 = document.getElementById('i_brand5_EA').value;
    var mandays75 = document.getElementById('i_brand5_EAM').value;
    var catalog75 = document.getElementById('i_total_EA').value;
    var totalusd75 = man75*mandays75*catalog75;

    i_tbrandusd5 = totalusd15+totalusd25+totalusd35+totalusd45+totalusd55+totalusd65+totalusd75;
    tbrandusd = i_tbrandusd1+i_tbrandusd2+i_tbrandusd3+i_tbrandusd4+i_tbrandusd5;

    if(tbrandusd>0) {
        var totalidr5 = totalusd15/tbrandusd*itotalbrand+totalusd25/tbrandusd*itotalbrand+totalusd35/tbrandusd*itotalbrand+totalusd45/tbrandusd*itotalbrand+totalusd55/tbrandusd*itotalbrand+totalusd65/tbrandusd*itotalbrand+totalusd75/tbrandusd*itotalbrand;
    } else {
        totalidr5=0;
    }

    i_totalbrand5=totalidr5;
    document.getElementById('i_totalbrand5').value=format(totalidr5) ; 
}

function i_totalbranded() {
    if(document.getElementById("band6").checked)
    {
        itotalbrand = i_agreed*1-ibpd*1-i_out_price*1;
    } else
    {
        itotalbrand = iprice*1-ibpd*1-i_out_price*1;
    }
alert("AAA");
    document.getElementById('i_totalbrand').value=format(itotalbrand);
    i_changebrand();
    if(itotalbrand*1<0 || iprice==0) { 
        document.getElementById('i_totalbrand').style.color="#333"; 
        implementation_tab_red();
    } else { 
        document.getElementById('i_totalbrand').style.color="#6e707e"; 
        implementation_tab_gray(); 
    }
}

function i_changebrand() {
    i_changebrand1();
    i_changebrand2();
    i_changebrand3();
    i_changebrand4();
    i_changebrand5();
    i_changebrand4();
    i_changebrand3();
    i_changebrand2();
    i_changebrand1();
    i_totalmandays();
}

function i_totalmandays() {
    var totmandays1 = document.getElementById('i_brand1_PD').value*document.getElementById('i_brand1_PDM').value+document.getElementById('i_brand2_PD').value*document.getElementById('i_brand2_PDM').value+document.getElementById('i_brand3_PD').value*document.getElementById('i_brand3_PDM').value+document.getElementById('i_brand4_PD').value*document.getElementById('i_brand4_PDM').value+document.getElementById('i_brand5_PD').value*document.getElementById('i_brand5_PDM').value;
    document.getElementById('i_totmandays_PD').value=totmandays1;

    var totmandays2 = document.getElementById('i_brand1_PM').value*document.getElementById('i_brand1_PMM').value+document.getElementById('i_brand2_PM').value*document.getElementById('i_brand2_PMM').value+document.getElementById('i_brand3_PM').value*document.getElementById('i_brand3_PMM').value+document.getElementById('i_brand4_PM').value*document.getElementById('i_brand4_PMM').value+document.getElementById('i_brand5_PM').value*document.getElementById('i_brand5_PMM').value;
    document.getElementById('i_totmandays_PM').value=totmandays2;

    var totmandays3 = document.getElementById('i_brand1_PC').value*document.getElementById('i_brand1_PCM').value+document.getElementById('i_brand2_PC').value*document.getElementById('i_brand2_PCM').value+document.getElementById('i_brand3_PC').value*document.getElementById('i_brand3_PCM').value+document.getElementById('i_brand4_PC').value*document.getElementById('i_brand4_PCM').value+document.getElementById('i_brand5_PC').value*document.getElementById('i_brand5_PCM').value;
    document.getElementById('i_totmandays_PC').value=totmandays3;

    var totmandays4 = document.getElementById('i_brand1_PA').value*document.getElementById('i_brand1_PAM').value+document.getElementById('i_brand2_PA').value*document.getElementById('i_brand2_PAM').value+document.getElementById('i_brand3_PA').value*document.getElementById('i_brand3_PAM').value+document.getElementById('i_brand4_PA').value*document.getElementById('i_brand4_PAM').value+document.getElementById('i_brand5_PA').value*document.getElementById('i_brand5_PAM').value;
    document.getElementById('i_totmandays_PA').value=totmandays4;

    var totmandays5 = document.getElementById('i_brand1_EE').value*document.getElementById('i_brand1_EEM').value+document.getElementById('i_brand2_EE').value*document.getElementById('i_brand2_EEM').value+document.getElementById('i_brand3_EE').value*document.getElementById('i_brand3_EEM').value+document.getElementById('i_brand4_EE').value*document.getElementById('i_brand4_EEM').value+document.getElementById('i_brand5_EE').value*document.getElementById('i_brand5_EEM').value;
    document.getElementById('i_totmandays_EE').value=totmandays5;

    var totmandays6 = document.getElementById('i_brand1_EP').value*document.getElementById('i_brand1_EPM').value+document.getElementById('i_brand2_EP').value*document.getElementById('i_brand2_EPM').value+document.getElementById('i_brand3_EP').value*document.getElementById('i_brand3_EPM').value+document.getElementById('i_brand4_EP').value*document.getElementById('i_brand4_EPM').value+document.getElementById('i_brand5_EP').value*document.getElementById('i_brand5_EPM').value;
    document.getElementById('i_totmandays_EP').value=totmandays6;

    var totmandays7 = document.getElementById('i_brand1_EA').value*document.getElementById('i_brand1_EAM').value+document.getElementById('i_brand2_EA').value*document.getElementById('i_brand2_EAM').value+document.getElementById('i_brand3_EA').value*document.getElementById('i_brand3_EAM').value+document.getElementById('i_brand4_EA').value*document.getElementById('i_brand4_EAM').value+document.getElementById('i_brand5_EA').value*document.getElementById('i_brand5_EAM').value;
    document.getElementById('i_totmandays_EA').value=totmandays7;
}

// Maintenance

function m_changebrand01() {
    mandays11 = document.getElementById('m_brand1_BU').value;
    var totalidr1 = mandays11*1+mandays21*1;
    document.getElementById('m_totalbrand1').value=format(totalidr1);
    document.getElementById('m_brand1_BU').value=format(mandays11);
    m_totalmandays();
}

function m_changebrand11() {
    mandays21 = document.getElementById('m_brand1_BUE').value;
    var totalidr1 = mandays11*1+mandays21*1;
    document.getElementById('m_totalbrand1').value=format(totalidr1);
    document.getElementById('m_brand1_BUE').value=format(mandays21);
    m_totalmandays();
}

function m_changebrand02() {
    mandays12 = document.getElementById('m_brand2_BU').value;
    var totalidr2 = mandays12*1+mandays22*1;
    document.getElementById('m_totalbrand2').value=format(totalidr2) ; 
    document.getElementById('m_brand2_BU').value=format(mandays12);
    m_totalmandays();
}

function m_changebrand12() {
    mandays22 = document.getElementById('m_brand2_BUE').value;
    var totalidr2 = mandays12*1+mandays22*1;
    document.getElementById('m_totalbrand2').value=format(totalidr2) ; 
    document.getElementById('m_brand2_BUE').value=format(mandays22);
    m_totalmandays();
}

function m_changebrand03() {
    mandays13 = document.getElementById('m_brand3_BU').value;
    var totalidr3 = mandays13*1+mandays23*1;
    document.getElementById('m_totalbrand3').value=format(totalidr3) ; 
    document.getElementById('m_brand3_BU').value=format(mandays13);
    m_totalmandays();
}

function m_changebrand13() {
    mandays23 = document.getElementById('m_brand3_BUE').value;
    var totalidr3 = mandays13*1+mandays23*1;
    document.getElementById('m_totalbrand3').value=format(totalidr3) ; 
    document.getElementById('m_brand3_BUE').value=format(mandays23);
    m_totalmandays();
}

function m_changebrand04() {
    mandays14 = document.getElementById('m_brand4_BU').value;
    var totalidr4 = mandays14*1+mandays24*1;
    document.getElementById('m_totalbrand4').value=format(totalidr4) ; 
    document.getElementById('m_brand4_BU').value=format(mandays14);
    m_totalmandays();
}

function m_changebrand14() {
    mandays24 = document.getElementById('m_brand4_BUE').value;
    var totalidr4 = mandays14*1+mandays24*1;
    document.getElementById('m_totalbrand4').value=format(totalidr4) ; 
    document.getElementById('m_brand4_BUE').value=format(mandays24);
    m_totalmandays();
}

function m_changebrand05() {
    mandays15 = document.getElementById('m_brand5_BU').value;
    var totalidr5 = mandays15*1+mandays25*1;
    document.getElementById('m_totalbrand5').value=format(totalidr5) ; 
    document.getElementById('m_brand5_BU').value=format(mandays15);
    m_totalmandays();
}

function m_changebrand15() {
    mandays25 = document.getElementById('m_brand5_BUE').value;
    var totalidr5 = mandays15*1+mandays25*1;
    document.getElementById('m_totalbrand5').value=format(totalidr5) ; 
    document.getElementById('m_brand5_BUE').value=format(mandays25);
    m_totalmandays();
}

function m_changebrand() {
    m_changebrand1(); 
    m_changebrand2();
    m_changebrand3();
    m_changebrand4();
    m_changebrand5();
    m_changebrand4();
    m_changebrand3();
    m_changebrand2();
    m_changebrand1();
    m_totalmandays();
}

function m_change_price() {
    mprice = document.getElementById('m_price').value;
    document.getElementById('m_price').value = format(mprice);
    document.getElementById('m_price_copy').value=format(mprice);
    m_totalmandays();
}

function m_change_bpd() {
    mbpd_price = document.getElementById('m_bpd_price').value;
    document.getElementById('m_bpd_price').value=format(mbpd_price);
    m_totalmandays();
}

// function m_change_out() {
//     mout_price = document.getElementById('m_out_price').value;
//     document.getElementById('m_out_price').value=format(mout_price);
//     m_totalmandays();
// }

function m_totalmandays() {
    var totmandays1 = mandays11*1+mandays12*1+mandays13*1+mandays14*1+mandays15*1;
    document.getElementById('m_totmandays_BU').value=format(totmandays1);

    var totmandays2 = mandays21*1+mandays22*1+mandays23*1+mandays24*1+mandays25*1;
    document.getElementById('m_totmandays_BUE').value=format(totmandays2); 

    document.getElementById('m_totalbrand').value=format(totmandays1*1+totmandays2*1);

    if(document.getElementById("band6").checked)
    {
        var mpackage = m_agreed*1-mbpd_price*1-m_out_price*1-totmandays1*1-totmandays2*1;
    } else
    {
        var mpackage = mprice*1-mbpd_price*1-m_out_price*1-totmandays1*1-totmandays2*1;
    }
    document.getElementById('m_package_price').value=format(mpackage);

    var mpackageother = mpackage-addon_price;
    document.getElementById('m_package_other_price').value=format(mpackageother);

    if((mpackage*1<0 || mprice<=0 || mpackageother<=0) && (document.getElementById('maintenance-tab').style.display=='')) { 
        document.getElementById('m_package_price').style.color="#333"; 
        document.getElementById('m_price').style.color="#333"; 
        document.getElementById('m_package_other_price').style.color="#333";
        document.getElementById('maintenance-tab').style.color="#333"; 
        // btn_save_disabled();
    } else { 
        document.getElementById('m_package_price').style.color="#6e707e";
        document.getElementById('m_price').style.color="#6e707e";
        document.getElementById('m_package_other_price').style.color="6e707e";
        document.getElementById('maintenance-tab').style.color="#6e707e";
        // btn_save_enabled();
    }

}    


// Warranty

function w_changebrand01() {
    w_mandays11 = document.getElementById('w_brand1_PEW').value;
    var totalidr1 = w_mandays11*1+w_mandays21*1;
    document.getElementById('w_totalbrand1').value=format(totalidr1);
    document.getElementById('w_brand1_PEW').value=format(w_mandays11);
    w_totalmandays();
}

function w_changebrand11() {
    w_mandays21 = document.getElementById('w_brand1_DEW').value;
    var totalidr1 = w_mandays11*1+w_mandays21*1;
    document.getElementById('w_totalbrand1').value=format(totalidr1);
    document.getElementById('w_brand1_DEW').value=format(w_mandays21);
    w_totalmandays();
}

function w_changebrand02() {
    w_mandays12 = document.getElementById('w_brand2_PEW').value;
    var totalidr2 = w_mandays12*1+w_mandays22*1;
    document.getElementById('w_totalbrand2').value=format(totalidr2) ; 
    document.getElementById('w_brand2_PEW').value=format(w_mandays12);
    w_totalmandays();
}

function w_changebrand12() {
    w_mandays22 = document.getElementById('w_brand2_DEW').value;
    var totalidr2 = w_mandays12*1+w_mandays22*1;
    document.getElementById('w_totalbrand2').value=format(totalidr2) ; 
    document.getElementById('w_brand2_DEW').value=format(w_mandays22);
    w_totalmandays();
}

function w_changebrand03() {
    w_mandays13 = document.getElementById('w_brand3_PEW').value;
    var totalidr3 = w_mandays13*1+w_mandays23*1;
    document.getElementById('w_totalbrand3').value=format(totalidr3) ; 
    document.getElementById('w_brand3_PEW').value=format(w_mandays13);
    w_totalmandays();
}

function w_changebrand13() {
    w_mandays23 = document.getElementById('w_brand3_DEW').value;
    var totalidr3 = w_mandays13*1+w_mandays23*1;
    document.getElementById('w_totalbrand3').value=format(totalidr3) ; 
    document.getElementById('w_brand3_DEW').value=format(w_mandays23);
    w_totalmandays();
}

function w_changebrand04() {
    w_mandays14 = document.getElementById('w_brand4_PEW').value;
    var totalidr4 = w_mandays14*1+w_mandays24*1;
    document.getElementById('w_totalbrand4').value=format(totalidr4) ; 
    document.getElementById('w_brand4_PEW').value=format(w_mandays14);
    w_totalmandays();
}

function w_changebrand14() {
    w_mandays24 = document.getElementById('w_brand4_DEW').value;
    var totalidr4 = w_mandays14*1+w_mandays24*1;
    document.getElementById('w_totalbrand4').value=format(totalidr4) ; 
    document.getElementById('w_brand4_DEW').value=format(w_mandays24);
    w_totalmandays();
}

function w_changebrand05() {
    w_mandays15 = document.getElementById('w_brand5_PEW').value;
    var totalidr5 = w_mandays15*1+w_mandays25*1;
    document.getElementById('w_totalbrand5').value=format(totalidr5) ; 
    document.getElementById('w_brand5_PEW').value=format(w_mandays15);
    w_totalmandays();
}

function w_changebrand15() {
    w_mandays25 = document.getElementById('w_brand5_DEW').value;
    var totalidr5 = w_mandays15*1+w_mandays25*1;
    document.getElementById('w_totalbrand5').value=format(totalidr5) ; 
    document.getElementById('w_brand5_DEW').value=format(w_mandays25);
    w_totalmandays();
}

function w_changebrand() {
    w_changebrand1(); 
    w_changebrand2();
    w_changebrand3();
    w_changebrand4();
    w_changebrand5();
    w_changebrand4();
    w_changebrand3();
    w_changebrand2();
    w_changebrand1();
    w_totalmandays();
}

function w_totalmandays() {
    var totmandays1 = w_mandays11*1+w_mandays12*1+w_mandays13*1+w_mandays14*1+w_mandays15*1;
    document.getElementById('w_totmandays_PEW').value=format(totmandays1);

    var totmandays2 = w_mandays21*1+w_mandays22*1+w_mandays23*1+w_mandays24*1+w_mandays25*1;
    document.getElementById('w_totmandays_DEW').value=format(totmandays2); 

    document.getElementById('w_totalbrand').value=format(totmandays1*1+totmandays2*1)

    // if((totmandays1*1-totmandays2*1)<0) { 
    //     document.getElementById('warranty-tab').style.color="red";
    //     // document.getElementById('w_price_po').style.color="red";
    // } else { 
        document.getElementById('warranty-tab').style.color="#6e707e"; 
    //     // document.getElementById('w_price_po').style.color="#6e707e"; 
    // } 
}    

function w_disablebrand() {
    document.getElementById('w_brand1_DEW').setAttribute('readonly', true);
    document.getElementById('w_brand2_PEW').setAttribute('readonly', true);
    document.getElementById('w_brand3_PEW').setAttribute('readonly', true);
    document.getElementById('w_brand4_PEW').setAttribute('readonly', true);
    document.getElementById('w_brand5_PEW').setAttribute('readonly', true);
    document.getElementById('w_brand1').value='Cisco';
    document.getElementById('w_brand1').setAttribute('readonly', true);
}


//Solution Change
function s_change() {
    sproduct = document.getElementById('DCCIP').value*1+document.getElementById('ECP').value*1+document.getElementById('BDAP').value*1+document.getElementById('DBMP').value*1+document.getElementById('ASAP').value*1+document.getElementById('SPP').value*1;
    document.getElementById('s_total_product').value = sproduct;
    
    sservice = document.getElementById('DCCIS').value*1+document.getElementById('ECS').value*1+document.getElementById('BDAS').value*1+document.getElementById('DBMS').value*1+document.getElementById('ASAS').value*1+document.getElementById('SPS').value*1;
    document.getElementById('s_total_service').value = sservice;

    if(sproduct!=100) { 
        document.getElementById('s_total_product').style.color="#333"; 
    } else { 
        document.getElementById('s_total_product').style.color="#6e707e";
    }
    if(sservice!=100) { 
        document.getElementById('s_total_service').style.color="#333"; 
    } else { 
        document.getElementById('s_total_service').style.color="#6e707e"; 
    }
    // if(sproduct==100 && (sservice==100 || sservice==0)) {
    if((sproduct==100 && sservice==100) || (sproduct==100 && sservice==0) || (sproduct==0 && sservice==100)) {
        // btn_save_enabled();
        document.getElementById('solution-tab').style.color='#333';
    } else {
        // btn_save_disabled();
        document.getElementById('solution-tab').style.color="#6e707e";
    }
}

// band change
function band_change() { 
    if(document.getElementById('band6').checked) {
        document.getElementById('i_agreed_price').removeAttribute('readonly'); 
        document.getElementById('m_agreed_price').removeAttribute('readonly'); 
        // document.getElementById('i_agreed_price').value = i_agreed; 
        // document.getElementById('m_agreed_price').value = m_agreed; 
    } else {
        document.getElementById('i_agreed_price').setAttribute('readonly',true); 
        document.getElementById('m_agreed_price').setAttribute('readonly',true); 
        // for(i=0;i<10;i++) {
        //     document.getElementById('i_agreed_price').value=0;
        //     document.getElementById('m_agreed_price').value=0;
        // }
    }
    i_totalbranded();
    m_totalmandays();
    i_changebrand();
}

//bundling
function change_bundling_i() {
    if(document.getElementById('i_bundling').checked) {
        document.getElementById('implementation-tab').style.display='';
        // implementation_tab_enabled();
        // document.getElementById('n_bundling').checked='';
    } else {
        document.getElementById('implementation-tab').style.display='none';
        // implementation_tab_disabled();
    }
}

function change_bundling_m() {
    if(document.getElementById('m_bundling').checked) {
        document.getElementById('maintenance-tab').style.display='';
        // document.getElementById('bundling_backup').style.display='';
        // document.getElementById('n_bundling').checked='';
        change_backup_unit();
    } else {
        document.getElementById('maintenance-tab').style.display='none';
        // document.getElementById('bundling_backup').style.display='none';
    }
}

function change_bundling_w() {
    if(document.getElementById('w_bundling').checked) {
        document.getElementById('warranty-tab').style.display='';
        // document.getElementById('n_bundling').checked='';
    } else {
        document.getElementById('warranty-tab').style.display='none';
    }
}

// function change_bundling_n() {
//     if(document.getElementById('n_bundling').checked) {
//         document.getElementById('i_bundling').checked='';
//         document.getElementById('m_bundling').checked='';
//         document.getElementById('w_bundling').checked='';
//         document.getElementById('bundling_backup').style.display='none';
//         // document.getElementById('agreed_price').style.display='none';
//         implementation_tab_disable();
//         maintenance_tab_disable();
//         warranty_tab_disable();
//         agreed_price_disabled();
//         solution_service_disabled();
//     } else {
//         document.getElementById('i_bundling').checked='checked';
//         document.getElementById('m_bundling').checked='checked';
//         document.getElementById('w_bundling').checked='checked';
//         document.getElementById('bundling_backup').style.display='';
//         document.getElementById('agreed_price').style.display='';
//         change_backup_unit();
//         implementation_tab_enabled();
//         maintenance_tab_enabled();
//         warranty_tab_enabled();
//         agreed_price_enabled();
//         solution_service_enabled();
//     }
// }

function change_backup_unit() {
    if(document.getElementById('backupEBU').checked) {
        document.getElementById('EBU').style.display='';
    } else {
        // if(document.getElementById("m_totmandays_BU").value>0) {
        //     if (confirm("Press a button!")) {
                document.getElementById("m_brand1_BU").value=0;
                document.getElementById("m_brand2_BU").value=0;
                document.getElementById("m_brand3_BU").value=0;
                document.getElementById("m_brand4_BU").value=0;
                document.getElementById("m_brand5_BU").value=0;
                m_changebrand01();
                m_changebrand02();
                m_changebrand03();
                m_changebrand04();
                m_changebrand05();
                document.getElementById('EBU').style.display='none';
            // } else {
            //     document.getElementById("backupEBU").checked=true;
            // }
        // }
    }
    if(document.getElementById('backupIBU').checked) {
        document.getElementById('IBU').style.display='';
    } else {
        document.getElementById('IBU').style.display='none';
        document.getElementById("m_brand1_BUE").value=0;
        document.getElementById("m_brand2_BUE").value=0;
        document.getElementById("m_brand3_BUE").value=0;
        document.getElementById("m_brand4_BUE").value=0;
        document.getElementById("m_brand5_BUE").value=0;
        m_changebrand11();
        m_changebrand12();
        m_changebrand13();
        m_changebrand14();
        m_changebrand15();
    }
}

function format_amount_idr() {
    document.getElementById('amount_idr').value=format(document.getElementById('amount_idr').value);
}

function format_amount_usd() {
    document.getElementById('amount_usd').value=format(document.getElementById('amount_usd').value);
}

function format_i_agreed_price() {
    i_agreed=document.getElementById('i_agreed_price').value;
    document.getElementById('i_agreed_price').value=format(i_agreed);
    i_changebrand();
    i_totalbranded();
    // band();
}

function format_m_agreed_price() {
    m_agreed=document.getElementById('m_agreed_price').value;
    document.getElementById('m_agreed_price').value=format(m_agreed);
    m_totalmandays();
    // band();
}

function band() {
    // Check Band
    agreed = (i_agreed*1+m_agreed*1)/(mprice*1+iprice*1)*100;
    if(agreed<=50) {
        document.getElementById("band6").checked='checked';
    } else if(agreed<=60) {
        document.getElementById("band5").checked='checked';
    } else if(agreed<=70) {
        document.getElementById("band4").checked='checked';
    } else if(agreed<=80) {
        document.getElementById("band3").checked='checked';
    } else if(agreed<=100) {
        document.getElementById("band2").checked='checked';
    } else if(agreed>100) {
        document.getElementById("band1").checked='checked';
    }
}

function format_m_addon_price_0() {
    m_addon_price_0 = document.getElementById('m_addon_price_0').value;
    document.getElementById('m_addon_price_0').value= format(m_addon_price_0);
    subtotal_addon();
}

function format_m_addon_price_1() {
    m_addon_price_1 = document.getElementById('m_addon_price_1').value;
    document.getElementById('m_addon_price_1').value= format(m_addon_price_1);
    subtotal_addon();
}

function format_m_addon_price_2() {
    m_addon_price_2 = document.getElementById('m_addon_price_2').value;
    document.getElementById('m_addon_price_2').value= format(m_addon_price_2);
    subtotal_addon();
}

function format_m_addon_price_3() {
    m_addon_price_3 = document.getElementById('m_addon_price_3').value;
    document.getElementById('m_addon_price_3').value= format(m_addon_price_3);
    subtotal_addon();
}

function format_m_addon_price_4() {
    m_addon_price_4 = document.getElementById('m_addon_price_4').value;
    document.getElementById('m_addon_price_4').value= format(m_addon_price_4);
    subtotal_addon();
}

function format_m_out_price_0() {
    m_out_price_0 = document.getElementById('m_out_price_0').value;
    document.getElementById('m_out_price_0').value= format(m_out_price_0);
    subtotal_m_out();
}

function format_m_out_price_1() {
    m_out_price_1 = document.getElementById('m_out_price_1').value;
    document.getElementById('m_out_price_1').value= format(m_out_price_1);
    subtotal_m_out();
}

function format_m_out_price_2() {
    m_out_price_2 = document.getElementById('m_out_price_2').value;
    document.getElementById('m_out_price_2').value= format(m_out_price_2);
    subtotal_m_out();
}

function format_m_out_price_3() {
    m_out_price_3 = document.getElementById('m_out_price_3').value;
    document.getElementById('m_out_price_3').value= format(m_out_price_3);
    subtotal_m_out();
}

function format_m_out_price_4() {
    m_out_price_4 = document.getElementById('m_out_price_4').value;
    document.getElementById('m_out_price_4').value= format(m_out_price_4);
    subtotal_m_out();
}

function subtotal_addon() {
    addon_price = m_addon_price_0*1+m_addon_price_1*1+m_addon_price_2*1+m_addon_price_3*1+m_addon_price_4*1;
    document.getElementById('m_addon_price').value = format(addon_price);
    m_totalmandays();
}

function subtotal_m_out() {
    m_out_price = m_out_price_0*1+m_out_price_1*1+m_out_price_2*1+m_out_price_3*1+m_out_price_4*1;
    document.getElementById('m_total_out_price').value = format(m_out_price); 
    m_totalmandays();
}

function format_i_out_price_0() { 
    i_out_price_0 = document.getElementById('i_out_price_0').value;
    document.getElementById('i_out_price_0').value= format(i_out_price_0);
    subtotal_i_out();
}

function format_i_out_price_1() {
    i_out_price_1 = document.getElementById('i_out_price_1').value;
    document.getElementById('i_out_price_1').value= format(i_out_price_1);
    subtotal_i_out();
}

function format_i_out_price_2() {
    i_out_price_2 = document.getElementById('i_out_price_2').value;
    document.getElementById('i_out_price_2').value= format(i_out_price_2);
    subtotal_i_out();
}

function format_i_out_price_3() {
    i_out_price_3 = document.getElementById('i_out_price_3').value;
    document.getElementById('i_out_price_3').value= format(i_out_price_3);
    subtotal_i_out();
}

function format_i_out_price_4() {
    i_out_price_4 = document.getElementById('i_out_price_4').value;
    document.getElementById('i_out_price_4').value= format(i_out_price_4);
    subtotal_i_out();
}

// function subtotal_addon() {
//     addon_price = m_addon_price_0*1+m_addon_price_1*1+m_addon_price_2*1+m_addon_price_3*1+m_addon_price_4*1;
//     document.getElementById('m_addon_price').value = format(addon_price);
//     m_totalmandays();
// }

// function subtotal_m_out() {
//     m_out_price = m_out_price_0*1+m_out_price_1*1+m_out_price_2*1+m_out_price_3*1+m_out_price_4*1;
//     document.getElementById('m_total_out_price').value = format(m_out_price); 
//     m_totalmandays();
// }

function subtotal_i_out() {
    i_out_price = i_out_price_0*1+i_out_price_1*1+i_out_price_2*1+i_out_price_3*1+i_out_price_4*1;
    document.getElementById('i_total_out_price').value = format(i_out_price);
    i_totalbranded();
    i_totalmandays();
}

function m_change_addon() {
    // format_i_out_price_0();
    // format_i_out_price_1();
    // format_i_out_price_2();
    // format_i_out_price_3();
    // format_i_out_price_4();
    // subtotal_i_out();
    format_m_out_price_0();
    format_m_out_price_1();
    format_m_out_price_2();
    format_m_out_price_3();
    format_m_out_price_4();
    // subtotal_m_out();
    format_m_addon_price_0();
    format_m_addon_price_1();
    format_m_addon_price_2();
    format_m_addon_price_3();
    format_m_addon_price_4();
}

function implementation_tab_enabled() {
    document.getElementById('implementation-tab').style.display='';
}

function implementation_tab_disabled() {
    document.getElementById('implementation-tab').style.display='none';
}

function implementation_tab_red() {
    document.getElementById('implementation-tab').style.color="red"; 
}

function implementation_tab_gray() {
    document.getElementById('implementation-tab').style.color="#6e707e"; 
}

function maintenance_tab_enabled() {
    document.getElementById('maintenance-tab').style.display='';
}

function maintenance_tab_disabled() {
    document.getElementById('maintenance-tab').style.display='none';
}

function maintenance_tab_red() {
    document.getElementById('maintenance-tab').style.color="red"; 
}

function maintenance_tab_gray() {
    document.getElementById('maintenance-tab').style.color="#6e707e"; 
}

function warranty_tab_enabled() {
    document.getElementById('warranty-tab').style.display='';
}

function warranty_tab_disabled() {
    document.getElementById('warranty-tab').style.display='none';
}

function warranty_tab_red() {
    document.getElementById('warranty-tab').style.color="red"; 
}

function warranty_tab_gray() {
    document.getElementById('warranty-tab').style.color="#6e707e"; 
}

function agreed_price_enabled() {
    document.getElementById('agreed_price').style.display='';
}

function agreed_price_disabled() {
    document.getElementById('agreed_price').style.display='none';
}

function backup_unit_enabled() {
    document.getElementById('backup_unit').style.display='';
}

function backup_unit_disabled() {
    document.getElementById('backup_unit').style.display='none';
}

function solution_service_enabled() {
    document.getElementById('labelservice').style.display='';
    document.getElementById('DCCIS').style.display='';
    document.getElementById('ECS').style.display='';
    document.getElementById('BDAS').style.display='';
    document.getElementById('DBMS').style.display='';
    document.getElementById('ASAS').style.display='';
    document.getElementById('SPS').style.display='';
    document.getElementById('s_total_service').style.display='';
}

function solution_service_disabled() {
    document.getElementById('labelservice').style.display='none';
    document.getElementById('DCCIS').style.display='none';
    document.getElementById('ECS').style.display='none';
    document.getElementById('BDAS').style.display='none';
    document.getElementById('DBMS').style.display='none';
    document.getElementById('ASAS').style.display='none';
    document.getElementById('SPS').style.display='none';
    document.getElementById('s_total_service').style.display='none';
}

// function btn_save_enabled() {
//     document.getElementById('btn_save').removeAttribute("disabled");
// }

// function btn_save_disabled() {
//     document.getElementById('btn_save').setAttribute("disabled",true);
// }

// function btn_approved_enabled() {
//     document.getElementById('btn_approved').removeAttribute("disabled");
// }

// function btn_approved_disabled() {
//     document.getElementById('btn_approved').setAttribute("disabled",true);
// }

function change_contract_type() {
    if(document.getElementById('contract_type').value=='Kontrak Payung') {
        document.getElementById('wo_type0').style.display='';
        document.getElementById('wo_type1').style.display='';
        document.getElementById('wo_type2').style.display='';
        document.getElementById('wo_type3').style.display='';
        document.getElementById('wo_type4').style.display='';
        document.getElementById('wo_type_title1').style.display='';
        document.getElementById('wo_type_title2').style.display='';
        document.getElementById('wo_type_title3').style.display='';
        document.getElementById('wo_type_title4').style.display='';
    } else {
        document.getElementById('wo_type0').style.display='none';
        document.getElementById('wo_type1').style.display='none';
        document.getElementById('wo_type2').style.display='none';
        document.getElementById('wo_type3').style.display='none';
        document.getElementById('wo_type4').style.display='none';
        document.getElementById('wo_type_title1').style.display='none';
        document.getElementById('wo_type_title2').style.display='none';
        document.getElementById('wo_type_title3').style.display='none';
        document.getElementById('wo_type_title4').style.display='none';
    }
}

function change_multiyears() { 
    if(document.getElementById('multiyears0').checked && document.getElementById("sbtype1").checked) {
        document.getElementById('multiyearsx').style.display='';
        // document.getElementById('wo_type0').checked='checked';
    } else {
        document.getElementById('multiyearsx').style.display='none';
    }
}

function change_mtos() {
    if(document.getElementById('m_tos_id2').checked==true) {
        document.getElementById('backup_unit').style.display='none';
    } else {
        document.getElementById('backup_unit').style.display='';
    } 
}

function w_changetotalbrand() {
    w_price = document.getElementById("w_price").value;
    document.getElementById("w_price").value=format(w_price);
}

function check_error() {

    var pesan_error='<ul>';
    var pesan_error1='';
    var pesan_tmp='';
    var pesan_tmp1=''
    var sambung='';
    var status=false;

    // PROJECT INFORMATION
    // Setup New Project
    if(document.getElementById("newproject1").checked || document.getElementById("newproject2").checked) {
        document.getElementById("newproject1").style.backgroundColor = "";
        document.getElementById("newproject2").style.backgroundColor = "";
    } else {
        pesan_tmp+='<li>Status Project belum dipilih.</li>';
        pesan_tmp1+=sambung+'Status Project belum dipilih';
        sambung=', ';
        status=true;
        document.getElementById("newproject1").style.backgroundColor = "#FFC7CE";
        document.getElementById("newproject2").style.backgroundColor = "#FFC7CE";
    }

    //Setup Type of Service Budget
    if(document.getElementById("sbtype0").checked || document.getElementById("sbtype1").checked) {
        document.getElementById("sbtype0").style.backgroundColor = "";
        document.getElementById("sbtype1").style.backgroundColor = "";

        if(document.getElementById("sbtype1").checked) {
            // Setup Multiyears
            if(document.getElementById("multiyears0").checked || document.getElementById("multiyears1").checked) {
                document.getElementById("multiyears0").style.backgroundColor = "";
                document.getElementById("multiyears1").style.backgroundColor = "";
                if(document.getElementById("multiyears0").checked) {
                    // Chect duration
                    if(document.getElementById("duration").value==0) {
                        document.getElementById("duration").style.backgroundColor ="#FFC7CE";
                        pesan_tmp+='<li>Duration masih kosong.</li>';
                        pesan_tmp1+'Durantion masih kosong';
                        sambung=', ';
                        status=true;
                    } else {
                        document.getElementById("duration").style.backgroundColor ="";
                    }
            
                    // Check WO Type
                    if(document.getElementById("wo_type0").checked || document.getElementById("wo_type1").checked || document.getElementById("wo_type2").checked || document.getElementById("wo_type3").checked || document.getElementById("wo_type4").checked) {
                        document.getElementById("wo_type0").style.backgroundColor="";
                        document.getElementById("wo_type1").style.backgroundColor="";
                        document.getElementById("wo_type2").style.backgroundColor="";
                        document.getElementById("wo_type3").style.backgroundColor="";
                        document.getElementById("wo_type4").style.backgroundColor="";
                    } else {
                        pesan_tmp+='<li>Work Order type belum dipilih.</li>';
                        pesan_tmp1+=sambung+'Work Order type belum dipilih';
                        sambung=', ';
                        status=true;
                        document.getElementById("wo_type0").style.backgroundColor="#FFC7CE";
                        document.getElementById("wo_type1").style.backgroundColor="#FFC7CE";
                        document.getElementById("wo_type2").style.backgroundColor="#FFC7CE";
                        document.getElementById("wo_type3").style.backgroundColor="#FFC7CE";
                        document.getElementById("wo_type4").style.backgroundColor="#FFC7CE";
                    }
                }
            } else {
                pesan_tmp+='<li>Multiyears belum dipilih.</li>';
                pesan_tmp1+=sambung+'Multiyears belum dipilih';
                sambung=', ';
                status=true;
                document.getElementById("multiyears0").style.backgroundColor = "#FFC7CE";
                document.getElementById("multiyears1").style.backgroundColor = "#FFC7CE";
            }

            // Setup Bundling
            if(document.getElementById("i_bundling").checked || document.getElementById("m_bundling").checked || document.getElementById("w_bundling").checked) {
                document.getElementById("i_bundling").style.background="";
                document.getElementById("m_bundling").style.background="";
                document.getElementById("w_bundling").style.background="";
            } else {
                pesan_tmp+='<li>Bundling Project belum dipilih.</li>';
                pesan_tmp1+=sambung+'Type Service Budget belum dipilih';
                sambung=', ';
                status=true;
                document.getElementById("i_bundling").style.background="#FFC7CE";
                document.getElementById("m_bundling").style.background="#FFC7CE";
                document.getElementById("w_bundling").style.background="#FFC7CE";
            }
        
        }
    } else {
        pesan_tmp+='<li>Type Service Budget belum dipilih.</li>';
        pesan_tmp1+=sambung+'Type Service Budgete belum dipilih';
        sambung=', ';
        status=true;
        document.getElementById("sbtype0").style.backgroundColor = "#FFC7CE";
        document.getElementById("sbtype1").style.backgroundColor = "#FFC7CE";
    }

    if(pesan_tmp!='') {
        pesan_error+='<li>Setup Project<ul>'+pesan_tmp+'</ul></li>';
        pesan_error1='Setup Project{'+pesan_tmp1+'},\r\n';
    }

    pesan_tmp='';
    pesan_tmp1='';
    sambung='';

   // TOTAL SOLUTION
    // Melakukan test untuk jumlah service yang harus 100%.
    if((sproduct==100 && sservice==0) || (sproduct==0 && sservice==100) || (sproduct==100 && sservice==100)) {
        document.getElementById("s_total_product").style.background="";
        document.getElementById("s_total_service").style.background="";
        document.getElementById("solution-tab").style.color='#333';
    } else {
        pesan_tmp += '<li>Solution harus 100%.</li>';
        pesan_tmp1+='Solution harus 100%';
        status=true;
        document.getElementById("s_total_product").style.background="#FFC7CE";
        document.getElementById("s_total_service").style.background="#FFC7CE";
        document.getElementById("solution-tab").style.color='red';
    }

    if(pesan_tmp!='') {
        pesan_error+='<li>Project Solution<ul>'+pesan_tmp+'</ul></li>';
        pesan_error1+='Project Solution {'+pesan_tmp1+'},\r\n';
    }

    pesan_tmp='';
    pesan_tmp1='';
    sambung='';

    if(document.getElementById("sbtype1").checked) {
        // IMPLEMENTATION
        if(document.getElementById('i_bundling').checked) {
            // check Service Catalog
            if(document.getElementById('i_tos_id0').checked || document.getElementById('i_tos_id1').checked || document.getElementById('i_tos_id2').checked) {
                document.getElementById("i_tos_id0").style.backgroundColor ="";
                document.getElementById("i_tos_id1").style.backgroundColor ="";
                document.getElementById("i_tos_id2").style.backgroundColor ="";
                document.getElementById("implementation-tab").style.color='#333';
            } else {
                pesan_tmp += '<li>Service Type belum dipilih.</li>';
                pesan_tmp1+='Service Type belum dipilih';
                sambung=', ';
                status=true;
                document.getElementById("i_tos_id0").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_tos_id1").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_tos_id2").style.backgroundColor ="#FFC7CE";
                document.getElementById("implementation-tab").style.color='red';
            }

            // Check Implementation Project Estimation
            if(document.getElementById("i_project_estimation").value==0) {
                pesan_tmp += '<li>Estimation Project Duration belum ditentukan.</li>';
                pesan_tmp1+=sambung+'Estimation Project Duration belum ditentukan';
                sambung=', ';
                status=true;
                document.getElementById("i_project_estimation").style.backgroundColor ="#FFC7CE";
                document.getElementById("implementation-tab").style.color='red';
            } else {
                document.getElementById("i_project_estimation").style.backgroundColor ="";
                document.getElementById("implementation-tab").style.color='#333';
            }

            // Check PO Customer untuk Implementation
            if(document.getElementById('i_bundling').checked && document.getElementById("i_price").value==0) {
                pesan_tmp += '<li>Implementasi Price (sesuai PO/SPK) masih kosong.</li>';
                pesan_tmp1+=sambung+'Implementasi Price (sesuai PO/SPK) masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("i_price").style.backgroundColor ="#FFC7CE";
                document.getElementById("implementation-tab").style.color='red';
            } else {
                document.getElementById("i_price").style.backgroundColor ="";
                document.getElementById("implementation-tab").style.color='#333';
            }

            // Check total mandays
            var confirm_mandays = false;
            // if(parseInt(i_totalbrand1)+parseInt(i_totalbrand2)+parseInt(i_totalbrand3)+parseInt(i_totalbrand4)+parseInt(i_totalbrand5)===parseInt(itotalbrand)) {
            if(parseInt(i_totalbrand1)>0 || parseInt(i_totalbrand2)>0 || parseInt(i_totalbrand3)>0 || parseInt(i_totalbrand4)>0 || parseInt(i_totalbrand5)>0) {
                document.getElementById("i_totalbrand1").style.backgroundColor ="";
                document.getElementById("i_totalbrand2").style.backgroundColor ="";
                document.getElementById("i_totalbrand3").style.backgroundColor ="";
                document.getElementById("i_totalbrand4").style.backgroundColor ="";
                document.getElementById("i_totalbrand5").style.backgroundColor ="";
                document.getElementById("implementation-tab").style.color='#333';
            } else {
                // pesan_tmp += '<li>Mandays Calculation masih kosong.</li>';
                // pesan_tmp1+=sambung+'Mandays Calculation masih kosong';
                // sambung=', ';
                // status=true;
                var confirm_mandays = true;
                document.getElementById("i_totalbrand1").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_totalbrand2").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_totalbrand3").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_totalbrand4").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_totalbrand5").style.backgroundColor ="#FFC7CE";
                document.getElementById("implementation-tab").style.color='red';
            }
            
            // Check if brand is null
            if(i_totalbrand1 > 0 && document.getElementById("i_brand1").value=="") {
                pesan_tmp += '<li>Brand1 masih kosong.</li>';
                pesan_tmp1+=sambung+'Brand1 masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("i_brand1").style.backgroundColor = "#FFC7CE";
            } else {
                document.getElementById("i_brand1").style.backgroundColor = "";
            }
            if(i_totalbrand2 > 0 && document.getElementById("i_brand2").value=="") {
                pesan_tmp += '<li>Brand2 masih kosong.</li>';
                pesan_tmp1+=sambung+'Brand2 masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("i_brand2").style.backgroundColor = "#FFC7CE";
            } else {
                document.getElementById("i_brand2").style.backgroundColor = "";
            }
            if(i_totalbrand3 > 0 && document.getElementById("i_brand3").value=="") {
                pesan_tmp += '<li>Brand3 masih kosong.</li>';
                pesan_tmp1+=sambung+'Brand3 masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("i_brand3").style.backgroundColor = "#FFC7CE";
            } else {
                document.getElementById("i_brand3").style.backgroundColor = "";
            }
            if(i_totalbrand4 > 0 && document.getElementById("i_brand4").value=="") {
                pesan_tmp += '<li>Brand4 masih kosong.</li>';
                pesan_tmp1+=sambung+'Brand4 masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("i_brand4").style.backgroundColor = "#FFC7CE";
            } else {
                document.getElementById("i_brand4").style.backgroundColor = "";
            }
            if(i_totalbrand5 > 0 && document.getElementById("i_brand5").value=="") {
                pesan_tmp += '<li>Brand5 masih kosong.</li>';
                pesan_tmp1+=sambung+'Brand5 masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("i_brand5").style.backgroundColor = "#FFC7CE";
            } else {
                document.getElementById("i_brand5").style.backgroundColor = "";
            }
        }

        if(pesan_tmp!='') {
            pesan_error+='<li>Implementation<ul>'+pesan_tmp+'</ul></li>';
            pesan_error1+='Implementation {'+pesan_tmp1+'},\r\n';
        }

        pesan_tmp='';
        pesan_tmp1='';
        sambung='';

        // MAINTENANCE
        if(document.getElementById('m_bundling').checked) {
            // Check Service Catalog.
            if(document.getElementById('m_tos_id0').checked || document.getElementById('m_tos_id1').checked || document.getElementById('m_tos_id2').checked || document.getElementById('m_tos_id3').checked) {

                if(document.getElementById('m_tos_id0').checked || document.getElementById('m_tos_id1').checked) {
                    // if(document.getElementById("backupEBU").checked) {
                        document.getElementById("backupEBU").style.backgroundColor='';
                        // document.getElementById("backupIBU").style.backgroundColor='';
                        document.getElementById("maintenance-tab").style.color='#333';
                    // } else {
                    //     if(document.getElementById("m_smo_id0").checked==false) {
                    //     pesan_tmp += '<li>Existing Backup Unit harus dipilih.</li>';
                    //     pesan_tmp1+='Backup Unit harus dipilih';
                    //     sambung=', ';
                    //     status=true;
                    //     document.getElementById("backupEBU").style.backgroundColor="#FFC7CE";
                    //     // document.getElementById("backupIBU").style.backgroundColor="#FFC7CE";
                    //     document.getElementById("maintenance-tab").style.color='red';
                    //     }
                    // }

                }



                // Check Total Brand di Maintenance
                if(document.getElementById("backupEBU").checked && document.getElementById("m_totmandays_BU").value==0 && (document.getElementById("m_tos_id0").checked || document.getElementById("m_tos_id1").checked)) {
                    pesan_tmp += '<li>Existing Backup Unit masih kosong.</li>';
                    pesan_tmp1+=sambung+'Existing Backup Unit masih kosong';
                    sambung=', ';
                    status=true;
                    document.getElementById("m_brand1_BU").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand2_BU").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand3_BU").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand4_BU").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand5_BU").style.backgroundColor ="#FFC7CE";
                    document.getElementById("maintenance-tab").style.color='red';
                } else {
                    document.getElementById("m_brand1_BU").style.backgroundColor ="";
                    document.getElementById("m_brand2_BU").style.backgroundColor ="";
                    document.getElementById("m_brand3_BU").style.backgroundColor ="";
                    document.getElementById("m_brand4_BU").style.backgroundColor ="";
                    document.getElementById("m_brand5_BU").style.backgroundColor ="";
                    document.getElementById("maintenance-tab").style.color='#333';
                }
                if(document.getElementById("backupIBU").checked && document.getElementById("m_totmandays_BUE").value==0 && (document.getElementById("m_tos_id0").checked || document.getElementById("m_tos_id1").checked)) {
                    pesan_tmp += '<li>Investment Backup Unit masih kosong.</li>';
                    pesan_tmp1+=sambung+'Investment Backup Unit masih kosong';
                    sambung=', ';
                    status=true;
                    document.getElementById("m_brand1_BUE").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand2_BUE").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand3_BUE").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand4_BUE").style.backgroundColor ="#FFC7CE";
                    document.getElementById("m_brand5_BUE").style.backgroundColor ="#FFC7CE";
                    document.getElementById("maintenance-tab").style.color='red';
                } else {
                    document.getElementById("m_brand1_BUE").style.backgroundColor ="";
                    document.getElementById("m_brand2_BUE").style.backgroundColor ="";
                    document.getElementById("m_brand3_BUE").style.backgroundColor ="";
                    document.getElementById("m_brand4_BUE").style.backgroundColor ="";
                    document.getElementById("m_brand5_BUE").style.backgroundColor ="";
                    document.getElementById("maintenance-tab").style.color='#333';
                }

                for(i=2;i<6;i++) {
                    if(document.getElementById("m_brand"+i).value=='' && (document.getElementById("m_brand"+i+"_BU").value>0 || document.getElementById("m_brand"+i+"_BUE").value>0)) {
                        pesan_tmp += '<li>Brand Backup Unit harus diisi.</li>';
                        pesan_tmp1+=sambung+'Brand Backup Unit harus diisi';
                        sambung=', ';
                        status=true;
                        document.getElementById("m_brand"+i).style.backgroundColor ="#FFC7CE";
                        document.getElementById("m_brand"+i).style.backgroundColor ="#FFC7CE";
                        document.getElementById("m_brand"+i).style.backgroundColor ="#FFC7CE";
                        document.getElementById("m_brand"+i).style.backgroundColor ="#FFC7CE";
                        document.getElementById("m_brand"+i).style.backgroundColor ="#FFC7CE";
                        document.getElementById("maintenance-tab").style.color='red';
                    } else {
                        document.getElementById("m_brand"+i).style.backgroundColor ="";
                        document.getElementById("m_brand"+i).style.backgroundColor ="";
                        document.getElementById("m_brand"+i).style.backgroundColor ="";
                        document.getElementById("m_brand"+i).style.backgroundColor ="";
                        document.getElementById("m_brand"+i).style.backgroundColor ="";
                        document.getElementById("maintenance-tab").style.color='#333';
                    }
                }

                document.getElementById("m_tos_id0").style.backgroundColor ="";
                document.getElementById("m_tos_id1").style.backgroundColor ="";
                document.getElementById("m_tos_id2").style.backgroundColor ="";
                document.getElementById("m_tos_id3").style.backgroundColor ="";
                // document.getElementById("m_tos_id4").style.backgroundColor ="";
            } else {
                pesan_tmp += '<li>Type of Service harus dipilih.</li>';
                pesan_tmp1+=sambung+'Type of Service harus dipilih';
                sambung=', ';
                status=true;
                document.getElementById("m_tos_id0").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id1").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id2").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id3").style.backgroundColor ="#FFC7CE";
                // document.getElementById("m_tos_id4").style.backgroundColor ="#FFC7CE";
                document.getElementById("maintenance-tab").style.color='red';
            }
            
            // Check Maintenance Project Estimation
            if(document.getElementById("m_project_estimation").value==0) {
                pesan_tmp += '<li>Estimation Project Duration masih kosong.</li>';
                pesan_tmp1+=sambung+'Estimation Project Duration masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("m_project_estimation").style.backgroundColor ="#FFC7CE";
                document.getElementById("maintenance-tab").style.color='red';
            } else {
                document.getElementById("m_project_estimation").style.backgroundColor ="";
                document.getElementById("maintenance-tab").style.color='#333';
            }

            // Check PO Customer untuk Maintenance
            if(document.getElementById("m_price").value==0) {
                pesan_tmp += '<li>Maintenance Price (sesuai PO/SPK) masih kosong.</li>';
                pesan_tmp1+=sambung+'Maintenance Price (sesuai PO/SPK) masih kosong';
                status=true;
                document.getElementById("m_price").style.backgroundColor ="#FFC7CE";
                document.getElementById("maintenance-tab").style.color='red';
            } else {
                document.getElementById("m_price").style.backgroundColor ="";
                document.getElementById("maintenance-tab").style.color='#333';
            }
        }

        if(pesan_tmp!='') {
            pesan_error+='<li>Maintenance<ul>'+pesan_tmp+'</ul></li>';
            pesan_error1+='Maintenance {'+pesan_tmp1+'},\r\n';
        }

        pesan_tmp='';
        pesan_tmp1='';
        sambung='';

        //WARRANTY
        if(document.getElementById("w_bundling").checked) {
            // check Service Catalog
            if(document.getElementById('w_tos_id0').checked || document.getElementById('w_tos_id1').checked) {
                document.getElementById("w_tos_id0").style.backgroundColor ="";
                document.getElementById("w_tos_id1").style.backgroundColor ="";
                document.getElementById("warranty-tab").style.color='#333';
            } else {
                pesan_tmp += '<li>Type Service belum dipilih.</li>';
                pesan_tmp1+='Type Service belum dipilih';
                sambung=', ';
                status=true;
                document.getElementById("w_tos_id0").style.backgroundColor ="#FFC7CE";
                document.getElementById("w_tos_id1").style.backgroundColor ="#FFC7CE";
                document.getElementById("warranty-tab").style.color='red';
            }

            // Check Maintenance Project Estimation
            if(document.getElementById("w_project_estimation").value==0) {
                pesan_tmp += '<li>Estimation Warranty Duration masih kosong.</li>';
                pesan_tmp1+=sambung+'Estimation Warranty Duration masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("w_project_estimation").style.backgroundColor ="#FFC7CE";
                document.getElementById("warranty-tab").style.color='red';
            } else {
                document.getElementById("w_project_estimation").style.backgroundColor ="";
                document.getElementById("warranty-tab").style.color='#333';
            }

            // Check PO Customer
            if(document.getElementById("w_price").value==0) {
                pesan_tmp += '<li>PO Customer (IDR) masih kosong.</li>';
                pesan_tmp1+=sambung+'PO Customer (IDR) masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("w_price").style.backgroundColor ="#FFC7CE";
                document.getElementById("warranty-tab").style.color='red';
            } else {
                document.getElementById("w_price").style.backgroundColor ="";
                document.getElementById("warranty-tab").style.color='#333';
            }
            
            // Check total mandays
            if(document.getElementById("w_totalbrand").value==0) {
                pesan_tmp += '<li>Nilai Extended Warranty masih kosong.</li>';
                pesan_tmp1+=sambung+'Nilai Extended Warranty masih kosong';
                sambung=', ';
                status=true;
                document.getElementById("w_brand1_PEW").style.backgroundColor ="#FFC7CE";
                document.getElementById("w_brand2_DEW").style.backgroundColor ="#FFC7CE";
                document.getElementById("w_brand3_DEW").style.backgroundColor ="#FFC7CE";
                document.getElementById("w_brand4_DEW").style.backgroundColor ="#FFC7CE";
                document.getElementById("w_brand5_DEW").style.backgroundColor ="#FFC7CE";
                document.getElementById("warranty-tab").style.color='red';
            } else {
                document.getElementById("w_brand1_PEW").style.backgroundColor ="";
                document.getElementById("w_brand2_DEW").style.backgroundColor ="";
                document.getElementById("w_brand3_DEW").style.backgroundColor ="";
                document.getElementById("w_brand4_DEW").style.backgroundColor ="";
                document.getElementById("w_brand5_DEW").style.backgroundColor ="";
                document.getElementById("warranty-tab").style.color='#333';
            }
        }
        if(pesan_tmp!='') {
            pesan_error+='<li>Warranty<ul>'+pesan_tmp+'</ul></li>';
            pesan_error1+='Warranty {'+pesan_tmp1+'}, \r\n';
        }

        // pesan_tmp='';
        // pesan_tmp1='';
        // sambung='';
        // // Check Agreed Price Implementation
        // if(document.getElementById("i_bundling").checked && iprice>0) {
        //     if(i_agreed>0) {
        //         document.getElementById("i_agreed_price").style.backgroundColor="";
        //     } else {
        //         pesan_tmp += '<li>Nilai Agreed Implementation masih kosong.</li>';
        //         pesan_tmp1+=sambung+'Nilai Agreed Implementation masih kosong';
        //         sambung=', ';
        //         status=true;
        //         document.getElementById("i_agreed_price").style.backgroundColor="#FFC7CE";
        //     }
        // }

        // // Check Agreed Price Maintenance
        // if(document.getElementById("m_bundling").checked && mprice>0) {
        //     if(m_agreed>0) {
        //         document.getElementById("m_agreed_price").style.backgroundColor="";
        //     } else {
        //         pesan_tmp += '<li>Nilai Agreed Maintenance masih kosong.</li>';
        //         pesan_tmp1+=sambung+'Nilai Agreed Maintenance masih kosong';
        //         status=true;
        //         document.getElementById("m_agreed_price").style.backgroundColor="#FFC7CE";
        //     }
        // }

        // if(pesan_tmp!='') {
        //     pesan_error+='<li>Agreed Price <ul>'+pesan_tmp+'</ul></li>';
        //     pesan_error1+='Agreed Price {'+pesan_tmp1+'}';
        // }

    }



    // Tampilkan pesan
    if(status==false) {
        document.getElementById("submit_service_budget").removeAttribute("disabled");
        document.getElementById("submit_pesan_error").style.display='none';
        document.getElementById("save_pesan_error").innerHTML='<p>Data sudah lengkap. Silahkan save dan Anda dapat mengajukan approval.</p>';
        document.getElementById("save_pesan_error").style.display='';
        document.getElementById("note_submited").style.display='';
        document.getElementById('note_save').innerHTML='Completed';
        // document.getElementById("save_service_budget").removeAttribute("disabled");
        if(confirm_mandays==true) {
            var pesannya = "<span class='text-danger'>Pastikan Mandays Calculation memang kosong.</span>";
            document.getElementById("save_pesan_confirm").innerHTML= pesannya;
            document.getElementById("save_pesan_confirm").style.display="";
        } else {
            document.getElementById("save_pesan_confirm").style.display="none";
        }

    } else {
        document.getElementById('submit_pesan_error').innerHTML='<div class="text-danger"><span class="fw-bold">Minta dilengkapi data;</span>'+pesan_error+'</div>';
        document.getElementById('save_pesan_error').innerHTML='<p class="text-danger fw-bold">Data yang Anda input masih kurang, silahkan untuk dilengkapi datanya!!!</p><p>Data yang kurang diberi tanda warna merah pada fieldnya.</p><p class="fw-bold">Minta dilengkapi data;</p>'+pesan_error;
        document.getElementById('note_save').innerHTML=pesan_error1;
        document.getElementById("submit_service_budget").setAttribute("disabled", true);
        document.getElementById("submit_pesan_error").style.display='';
        document.getElementById("save_pesan_error").style.display='';
        document.getElementById("note_submited").style.display='none';
        // document.getElementById("save_service_budget").setAttribute("disabled", true);
    }

}