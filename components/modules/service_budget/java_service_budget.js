$(function(){
    $("#parent_id").select2();
    $("#previous_id").select2();
}); 
  
function show_console(axd)
{
    const d = new Date();
    let hour = d.getHours();
    let minutes = d.getMinutes();
    let seconds = d.getSeconds();
    let microseconds = addZero(d.getMilliseconds(), 5);
    console.log(hour+":"+minutes+":"+seconds+":"+microseconds+" "+axd);
}

function band_change_v2(axd)
{
    show_console("band_change_v2("+axd+")");
    var i_price = deformat(document.getElementById("i_price").value);
    var i_price_list = deformat(document.getElementById("i_price_list").value);
    var i_agreed_price = deformat(document.getElementById("i_agreed_price").value);
    var m_price = deformat(document.getElementById("m_price").value);
    var m_price_list = deformat(document.getElementById("m_price_list").value);
    var m_agreed_price = deformat(document.getElementById("m_agreed_price").value);
    var i_total_out_price = deformat(document.getElementById("i_total_out_price").value);
    var m_total_out_price = deformat(document.getElementById("m_total_out_price").value);

//    var i_band = deformat(i_price) / deformat(i_price_list);
//    var m_band = deformat(m_price) / deformat(m_price_list);
    var i_band = (i_price-i_total_out_price) / (i_price_list-i_total_out_price);
    var m_band = (m_price-m_total_out_price) / (m_price_list-m_total_out_price);
    if(document.getElementById("i_bundling").checked && document.getElementById("m_bundling").checked)
    {
        // var band = (i_band + m_band)/2;
//        var band = (deformat(i_price)*1 +  deformat(m_price)*1) / (deformat(i_price_list)*1 + deformat(m_price_list)*1)
        var band = ((i_price-i_total_out_price)*1 +  (m_price-m_total_out_price)*1) / ((i_price_list-i_total_out_price)*1 + (m_price_list-m_total_out_price)*1)
        console.log("Band = (("+i_price+" - "+i_total_out_price+") + ("+m_price+" - "+m_total_out_price+")) / (("+i_price_list+" - "+i_total_out_price+") + ("+m_price_list+" - "+m_total_out_price+")) = "+band);
    } else
    if(document.getElementById("i_bundling").checked && document.getElementById("m_bundling").checked==false)
    {
        var band = i_band;
        console.log("bbb"+band);
    } else
    if(document.getElementById("i_bundling").checked==false && document.getElementById("m_bundling").checked)
    {
        var band = m_band;
        console.log("ccc"+band);
    }

    document.getElementById('i_agreed_price').setAttribute('readonly', true);
    document.getElementById('m_agreed_price').setAttribute('readonly', true);
    if(band>1)
    {
        document.getElementById('band1').checked = 'checked';
    } else if(band>0.8)
    {
        document.getElementById('band2').checked = 'checked';
    } else if(band>0.7)
    {
        document.getElementById('band3').checked = 'checked';
    } else if(band>0.6)
    {
        document.getElementById('band4').checked = 'checked';
    } else if(band>0.5)
    {
        document.getElementById('band5').checked = 'checked';
    } else
    {
        document.getElementById('band6').checked = 'checked';
        document.getElementById('i_agreed_price').removeAttribute('readonly');
        document.getElementById('m_agreed_price').removeAttribute('readonly');
    }
    // if(band>0.5 || band==0)
    if(band>0.5)
    { 
        show_console("band>0.5");
        document.getElementById('i_agreed_price').value = 0;
        document.getElementById('m_agreed_price').value = 0;
    } else
    if(band>0)
    {
        show_console("band>0");
        document.getElementById('i_agreed_price').value = format(i_agreed_price);
        document.getElementById('m_agreed_price').value = format(m_agreed_price);
    }
    show_console("band else");
    document.getElementById("i_price_list").value = format(i_price_list);
    document.getElementById("m_price_list").value = format(m_price_list);
   mandays_change(16);
}

function get_renewal(axd)
{
    show_console("get_renewal("+axd+")");
    if(document.getElementById("newproject0").checked)
    {
        document.getElementById("Renewal").style.display="";
        document.getElementById("New").style.display="";
    } else
    {
        document.getElementById("Renewal").style.display="none";
        document.getElementById("New").style.display="none";
    }
}

function sub_project(axd)
{
    show_console("sub_project("+axd+")");
    if(document.getElementById("subproject1").checked)
    {
        document.getElementById("subproject").style.display="";
    } else
    {
        document.getElementById("subproject").style.display="none";
    }
}

function getlen(axd) {
    show_console("getlen("+axd+")");
    len = document.getElementById("note_project_name_internal").value;
    length = len.length;
    document.getElementById("demo").innerHTML = length;
    if(length>100) {
        document.getElementById("demo").style.color = "red";
    } else {
        document.getElementById("demo").style.color = "black";
    }
}

// Formula Implementation
function i_change_price(axd) {
    show_console("i_change_price("+axd+")");
    iprice = document.getElementById('i_price').value;
    document.getElementById('i_price').value=format(iprice);
    document.getElementById('i_price_copy').value=format(iprice);
    band_change_v2(5);
    mandays_change(17);
}

function m_change_price(axd) {
    show_console("m_change("+axd+")");
    mprice = document.getElementById('m_price').value;
    document.getElementById('m_price').value = format(mprice);
    document.getElementById('m_price_copy').value=format(mprice);
    band_change_v2(6);
}

function i_change_bpd(axd) {
    show_console("i_change_bpd("+axd+")");
    ibpd = document.getElementById('i_bpd_price').value;
    document.getElementById('i_bpd_price').value=format(ibpd);
    mandays_change(18);
}

function m_change_bpd(axd) {
    show_console("m_change_bpd("+axd+")");
    ibpd = document.getElementById('m_bpd_price').value;
    document.getElementById('m_bpd_price').value=format(ibpd);
    check_packages();
}

// Service Budget Type
function sbtypex(axd) {
    show_console("sbtype("+axd+")");
    if(document.getElementById('sbtype1').checked) {
        document.getElementById('bundling_project').style.display='';
        document.getElementById('multiyearsx').style.display='';
        document.getElementById('multiyearsy').style.display='';
        document.getElementById('official').style.display='';
        document.getElementById('agreed_price').style.display='';
        document.getElementById('labelservice').style.display='';
        document.getElementById('DCCIS').style.display='';
        document.getElementById('ECS').style.display='';
        document.getElementById('BDAS').style.display='';
        document.getElementById('DBMS').style.display='';
        document.getElementById('ASAS').style.display='';
        document.getElementById('SPS').style.display='';
        document.getElementById('s_total_service').style.display='';
        document.getElementById('implementation-tab').style.display = '';
        document.getElementById('maintenance-tab').style.display = '';
        document.getElementById('warranty-tab').style.display = '';
        document.getElementById('sub-solution').style.display = '';
    } else 
    if(document.getElementById('sbtype0').checked) 
    { 
        document.getElementById('bundling_project').style.display='none';
        document.getElementById('multiyearsx').style.display='none';
        document.getElementById('multiyearsy').style.display='none';
        document.getElementById('labelservice').style.display='none';
        document.getElementById('DCCIS').style.display='none';
        document.getElementById('ECS').style.display='none';
        document.getElementById('BDAS').style.display='none';
        document.getElementById('DBMS').style.display='none';
        document.getElementById('ASAS').style.display='none';
        document.getElementById('SPS').style.display='none';
        document.getElementById('s_total_service').style.display='none';
        document.getElementById('implementation-tab').style.display = 'none';
        document.getElementById('maintenance-tab').style.display = 'none';
        document.getElementById('warranty-tab').style.display = 'none';
        document.getElementById('agreed_price').style.display='none';
        document.getElementById('sub-solution').style.display = 'none';
        
        show_console("amount_idr");
        if(deformat(document.getElementById("amount_idr").value)>200000000)
        {
            document.getElementById("official").style.display = "";
        } else
        {
            document.getElementById("official").style.display = "none";
        }
    } else
    { 
        // sbtype3
        document.getElementById('bundling_project').style.display='none';
        document.getElementById('multiyearsx').style.display='none';
        document.getElementById('multiyearsy').style.display='none';
        document.getElementById('official').style.display='';
        document.getElementById('labelservice').style.display='';
        document.getElementById('DCCIS').style.display='';
        document.getElementById('ECS').style.display='';
        document.getElementById('BDAS').style.display='';
        document.getElementById('DBMS').style.display='';
        document.getElementById('ASAS').style.display='';
        document.getElementById('SPS').style.display='';
        document.getElementById('s_total_service').style.display='';
        document.getElementById('implementation-tab').style.display = 'none';
        document.getElementById('maintenance-tab').style.display = 'none';
        document.getElementById('warranty-tab').style.display = 'none';
        document.getElementById('agreed_price').style.display='none';
        document.getElementById('sub-solution').style.display = 'none';
    }
    change_multiyears(3);
}

// Multi Years Setup
function change_multiyears(axd) { 
    show_console("change_multiyears("+axd+")");
    if(document.getElementById('multiyears0').checked && document.getElementById("sbtype1").checked) {
        document.getElementById('multiyearsx').style.display='';
    } else {
        document.getElementById('multiyearsx').style.display='none';
    }
}

$(document).ready(function(){
    $("#i_bundling").click(function(){
        if(document.getElementById('i_bundling').checked==false) {
            if (confirm("All implementation data will be deleted.\r\nAre you sure it will be deleted?") == true) {
                document.getElementById('implementation-tab').style.display='none';
                document.getElementById("i_agreed_0").style.display = "none";
                document.getElementById("i_agreed_1").style.display = "none";
                document.getElementById("i_agreed_2").style.display = "none";
                document.getElementById("i_agreed_3").style.display = "none";
			} else
            {
                document.getElementById('i_bundling').checked="checked";
            }
        } else
        {
            document.getElementById('implementation-tab').style.display='';
            document.getElementById("implementation-tab").style.color='#333';
            document.getElementById("i_agreed_0").style.display = "";
            document.getElementById("i_agreed_1").style.display = "";
            document.getElementById("i_agreed_2").style.display = "";
            document.getElementById("i_agreed_3").style.display = "";
        };
        // agreed_price();
    });
    $("#m_bundling").click(function(){
        if(document.getElementById('m_bundling').checked==false) {
            if (confirm("All maintenance data will be deleted.\r\nAre you sure it will be deleted?") == true) {
                document.getElementById('maintenance-tab').style.display='none';
                document.getElementById("m_agreed_0").style.display = "none";
                document.getElementById("m_agreed_1").style.display = "none";
                document.getElementById("m_agreed_2").style.display = "none";
                document.getElementById("m_agreed_3").style.display = "none";
            } else
            {
                document.getElementById('m_bundling').checked="checked";
            }
        } else
        {
            document.getElementById('maintenance-tab').style.display='';
            document.getElementById("m_agreed_0").style.display = "";
            document.getElementById("m_agreed_1").style.display = "";
            document.getElementById("m_agreed_2").style.display = "";
            document.getElementById("m_agreed_3").style.display = "";
        };
        // agreed_price();
    });
    $("#w_bundling").click(function(){
        if(document.getElementById('w_bundling').checked==false) {
            if (confirm("All warranty data will be deleted.\r\nAre you sure it will be deleted?") == true) {
                document.getElementById('warranty-tab').style.display='none';
			} else
            {
                document.getElementById('w_bundling').checked="checked";
            }
        } else
        {
            document.getElementById('warranty-tab').style.display='';
            document.getElementById('warranty-tab').style.color='#333';
        };
    });
});

// function agreed_price()
// {
//     if(document.getElementById('i_bundling').checked==false && document.getElementById('m_bundling').checked==false) {
//         document.getElementById("agreed_price").style.display = "none";
//     } else
//     {
//         document.getElementById("agreed_price").style.display = "";
//     }
// }

// function sb_temporary()
// {
//     if(document.getElementById("temporary0").checked)
//     {
//         document.getElementById('temporary_note').style.display='';
//     } else
//     {
//         document.getElementById('temporary_note').style.display='none';
//     }
// }

function total_reportingx(axd)
{
    show_console("total_reportingx("+axd+")");
    const plan_reporting = document.getElementById("plan_reporting").value;
    const addon_reporting = document.getElementById("addon_reporting").value;
    var total_reporting = plan_reporting*1+addon_reporting*1;
    document.getElementById("total_reporting").value = total_reporting;
}

function total_preventivex(axd)
{
    show_console("change_multiyears("+axd+")");
    const plan_reporting = document.getElementById("plan_preventive").value;
    const addon_reporting = document.getElementById("addon_preventive").value;
    var total_reporting = plan_reporting*1+addon_reporting*1;
    document.getElementById("total_preventive").value = total_reporting;
}

function total_ticketx(axd)
{
    show_console("total_ticketx("+axd+")");
    const plan_reporting = document.getElementById("plan_ticket").value;
    const addon_reporting = document.getElementById("addon_ticket").value;
    var total_reporting = plan_reporting*1+addon_reporting*1;
    document.getElementById("total_ticket").value = total_reporting;
}

//Solution Change
function s_change(axd) {
    show_console("s_change("+axd+")");
    sproduct = document.getElementById('DCCIP').value*1+document.getElementById('ECP').value*1+document.getElementById('BDAP').value*1+document.getElementById('DBMP').value*1+document.getElementById('ASAP').value*1+document.getElementById('SPP').value*1;
    document.getElementById('s_total_product').value = sproduct;
    
    sservice = document.getElementById('DCCIS').value*1+document.getElementById('ECS').value*1+document.getElementById('BDAS').value*1+document.getElementById('DBMS').value*1+document.getElementById('ASAS').value*1+document.getElementById('SPS').value*1;
    document.getElementById('s_total_service').value = sservice;

    if(sproduct!=100) { 
        document.getElementById('s_total_product').style.backgroundColor = "#FFC7CE"; 
    } else { 
        document.getElementById('s_total_product').style.backgroundColor = "#eaecf4"; 
    }
    if(sservice!=100) { 
        document.getElementById('s_total_service').style.backgroundColor = "#FFC7CE"; 
    } else { 
        document.getElementById('s_total_service').style.backgroundColor = "#eaecf4"; 
    }
    if((sproduct==100 && sservice==100) || (sproduct==100 && sservice==0) || (sproduct==0 && sservice==100)) {
        document.getElementById('solution-tab').style.color='#333';
    } else {
        document.getElementById('solution-tab').style.color="#ee0000";
    }
}

// IMPLEMENTATION
function mandays_total()
{
    show_console("mandays_total");
    var band6 = document.getElementById("band6");
    var i_agreed = deformat(document.getElementById("i_agreed_price").value);
    var i_price = deformat(document.getElementById("i_price_list").value);
    var i_bpd = deformat(document.getElementById("i_bpd_price").value);
    var i_out = deformat(document.getElementById("i_total_out_price").value);
    if(band6.checked)
    {
        TotalBrand = i_agreed*1-i_bpd*1-i_out*1;
    } else
    {
        TotalBrand = i_price*1-i_bpd*1-i_out*1;
    }
    document.getElementById("total_mandays").value = format(TotalBrand);

}
function mandays_change(axd)
{
    show_console("mandays_change("+axd+")");
    var total_rate = deformat(document.getElementById("total_rate_mandays").value);
    mandays_total();
    // ixx = 0;
    var nCisco = document.querySelectorAll(".total_mandays");
    // nTotal = 0;
    mTotal = 0;
    // nCisco.forEach(function(input) {
    // var total_rate = 0;
    // for(ixx=0; ixx<(nCisco.length); ixx++)
    // {
    //     total_rate += deformat(document.getElementById("mandays["+ixx+"]['PDRate']").value);
    // }
    // alert(total_rate);

    for(ixx=0; ixx<(nCisco.length/7); ixx++)
    {
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PDMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PDRate']").value);
        nTotalRate1 = mandaysx*ratex;
        nGTotalRate = nTotalRate1;
        mTotal += nTotalRate1;
        document.getElementById("mandays["+ixx+"]['PDtotalrate']").value = nTotalRate1;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal = nTotal;
        // document.getElementById("mandays["+ixx+"]['PDtotal']").value = format(nTotal);
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PMMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PMRate']").value);
        nTotalRate2 = mandaysx*ratex;
        nGTotalRate += nTotalRate2;
        mTotal += nTotalRate2;
        document.getElementById("mandays["+ixx+"]['PMtotalrate']").value = nTotalRate2;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal += nTotal*1;
        // document.getElementById("mandays["+ixx+"]['PMtotal']").value = format(nTotal);
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PCMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PCRate']").value);
        nTotalRate3 = mandaysx*ratex;
        nGTotalRate += nTotalRate3;
        mTotal += nTotalRate3;
        document.getElementById("mandays["+ixx+"]['PCtotalrate']").value = nTotalRate3;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal += nTotal*1;
        // document.getElementById("mandays["+ixx+"]['PCtotal']").value = format(nTotal);
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PAMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PARate']").value);
        nTotalRate4 = mandaysx*ratex;
        nGTotalRate += nTotalRate4;
        mTotal += nTotalRate4;
        document.getElementById("mandays["+ixx+"]['PAtotalrate']").value = nTotalRate4;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal += nTotal*1;
        // document.getElementById("mandays["+ixx+"]['PAtotal']").value = format(nTotal);
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['EEMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['EERate']").value);
        nTotalRate5 = mandaysx*ratex;
        nGTotalRate += nTotalRate5;
        mTotal += nTotalRate5;
        document.getElementById("mandays["+ixx+"]['EEtotalrate']").value = nTotalRate5;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal += nTotal*1;
        // document.getElementById("mandays["+ixx+"]['EEtotal']").value = format(nTotal);
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['EPMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['EPRate']").value);
        nTotalRate6 = mandaysx*ratex;
        nGTotalRate += nTotalRate6;
        mTotal += nTotalRate6;
        document.getElementById("mandays["+ixx+"]['EPtotalrate']").value = nTotalRate6;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal += nTotal*1;
        // document.getElementById("mandays["+ixx+"]['EPtotal']").value = format(nTotal);
        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['EAMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['EARate']").value);
        nTotalRate7 = mandaysx*ratex;
        nGTotalRate += nTotalRate7;
        mTotal += nTotalRate7;
        document.getElementById("mandays["+ixx+"]['EAtotalrate']").value = nTotalRate7;
        // nTotal = nTotalRate / total_rate * TotalBrand;
        // mTotal += nTotal*1;
        // document.getElementById("mandays["+ixx+"]['EAtotal']").value = format(nTotal);
        // document.getElementById("total_mandays["+ixx+"]").value = format(mTotal);
        // ixx++;
        document.getElementById("total_rate_mandays["+ixx+"]").value = nGTotalRate;
        document.getElementById("total_rate_mandays").value = mTotal;
    }
    for(ixx=0; ixx<(nCisco.length/7); ixx++)
    {
        TotalMandays = deformat(document.getElementById("total_mandays").value);
        mTotal = document.getElementById("total_rate_mandays").value;

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PDMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PDRate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal = Total;
        document.getElementById("mandays["+ixx+"]['PDTotal']").value = format(Total);

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PMMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PMRate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal += Total;
        document.getElementById("mandays["+ixx+"]['PMTotal']").value = format(Total);

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PCMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PCRate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal += Total;
        document.getElementById("mandays["+ixx+"]['PCTotal']").value = format(Total);

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['PAMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['PARate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal += Total;
        document.getElementById("mandays["+ixx+"]['PATotal']").value = format(Total);

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['EEMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['EERate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal += Total;
        document.getElementById("mandays["+ixx+"]['EETotal']").value = format(Total);

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['EPMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['EPRate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal += Total;
        document.getElementById("mandays["+ixx+"]['EPTotal']").value = format(Total);

        var mandaysx = deformat(document.getElementById("mandays["+ixx+"]['EAMandays']").value);
        var ratex = deformat(document.getElementById("mandays["+ixx+"]['EARate']").value);
        nTotalRate = mandaysx*ratex;
        Total = nTotalRate / mTotal * TotalMandays;
        xTotal += Total;
        document.getElementById("mandays["+ixx+"]['EATotal']").value = format(Total);

        document.getElementById("total_mandays["+ixx+"]").value = format(xTotal);
    }
    check_packages();
}

function change_catalog(axd, ixx)
{
    show_console("change_catalog("+axd+", "+ixx+")");
    var nCisco = document.querySelectorAll(".total_mandays");
    xCat = axd;
    
    if(xCat==1)
    {
        document.getElementById("mandays["+ixx+"]['PDRate']").value = document.getElementById("catalogPDMandays").value;
        document.getElementById("mandays["+ixx+"]['PMRate']").value = document.getElementById("catalogPMMandays").value;
        document.getElementById("mandays["+ixx+"]['PCRate']").value = document.getElementById("catalogPCMandays").value;
        document.getElementById("mandays["+ixx+"]['PARate']").value = document.getElementById("catalogPAMandays").value;
        document.getElementById("mandays["+ixx+"]['EERate']").value = document.getElementById("catalogEEMandays").value;
        document.getElementById("mandays["+ixx+"]['EPRate']").value = document.getElementById("catalogEPMandays").value;
        document.getElementById("mandays["+ixx+"]['EARate']").value = document.getElementById("catalogEAMandays").value;
        document.getElementById("mandaysLabel").innerHTML = "Mandays";
    } else
    if(xCat==2)
    {
        document.getElementById("mandays["+ixx+"]['PDRate']").value = document.getElementById("catalogPDManmonths").value;
        document.getElementById("mandays["+ixx+"]['PMRate']").value = document.getElementById("catalogPMManmonths").value;
        document.getElementById("mandays["+ixx+"]['PCRate']").value = document.getElementById("catalogPCManmonths").value;
        document.getElementById("mandays["+ixx+"]['PARate']").value = document.getElementById("catalogPAManmonths").value;
        document.getElementById("mandays["+ixx+"]['EERate']").value = document.getElementById("catalogEEManmonths").value;
        document.getElementById("mandays["+ixx+"]['EPRate']").value = document.getElementById("catalogEPManmonths").value;
        document.getElementById("mandays["+ixx+"]['EARate']").value = document.getElementById("catalogEAManmonths").value;
        document.getElementById("mandaysLabel").innerHTML = "Manmonths";
    } else
    {
        document.getElementById("mandays["+ixx+"]['PDRate']").value = document.getElementById("catalogPDManyears").value;
        document.getElementById("mandays["+ixx+"]['PMRate']").value = document.getElementById("catalogPMManyears").value;
        document.getElementById("mandays["+ixx+"]['PCRate']").value = document.getElementById("catalogPCManyears").value;
        document.getElementById("mandays["+ixx+"]['PARate']").value = document.getElementById("catalogPAManyears").value;
        document.getElementById("mandays["+ixx+"]['EERate']").value = document.getElementById("catalogEEManyears").value;
        document.getElementById("mandays["+ixx+"]['EPRate']").value = document.getElementById("catalogEPManyears").value;
        document.getElementById("mandays["+ixx+"]['EARate']").value = document.getElementById("catalogEAManyears").value;
        document.getElementById("mandaysLabel").innerHTML = "Manyears";
    }
    mandays_change(19);
}


// EXTENDED WARRANTY
function w_change_price(axd) {
    show_console("w_change("+axd+")");
    wprice = document.getElementById('w_price').value;
    document.getElementById('w_price').value=format(wprice);
}
function warranty_Cisco_pricex() {
    show_console("warranty_Cisco_pricex()");
    wprice = document.getElementById('warranty_Cisco_price[0]').value;
    document.getElementById('warranty_Cisco_price[0]').value=format(wprice);
}
function warranty_addon_pricex(iwarranty_Addon_price) {
    show_console("warranty_addon_pricex("+iwarranty_Addon_price+")");
    wprice = document.getElementById("warranty_addon_price["+iwarranty_Addon_price+"]").value; 
    document.getElementById('warranty_addon_price['+iwarranty_Addon_price+']').value=format(wprice);
    warranty_addon_pricex2();
}
function warranty_addon_pricex2()
{
    show_console("warranty_addon_pricex2()");
    var nCisco = document.querySelectorAll(".warranty_addon");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("warranty_addon_price").value = format(nTotal);
}
function warranty_nCisco_pricex(iwarranty_nCisco_price) {
    show_console("warranty_nCisco_pricex("+iwarranty_nCisco_price+")");
    wprice = document.getElementById("warranty_nCisco_price["+iwarranty_nCisco_price+"]").value; 
    document.getElementById('warranty_nCisco_price['+iwarranty_nCisco_price+']').value=format(wprice);
    var nCisco = document.querySelectorAll(".non_cisco");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("warranty_nCisco_price").value = format(nTotal);
}
function implementation_outsourcing_price(iwarranty_nCisco_price) {
    show_console("implementation_outsourcing_price("+iwarranty_nCisco_price+")");
    wprice = document.getElementById("i_out_price["+iwarranty_nCisco_price+"]").value; 
    document.getElementById('i_out_price['+iwarranty_nCisco_price+']').value=format(wprice);
    var nCisco = document.querySelectorAll(".i_price");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("i_total_out_price").value = format(nTotal);
    check_packages();
    mandays_change(15);
}
function maintenance_outsourcing_price(iwarranty_nCisco_price) {
    show_console("maintenance_outsourcing_price("+iwarranty_nCisco_price+")");
    wprice = document.getElementById("m_out_price["+iwarranty_nCisco_price+"]").value; 
    document.getElementById('m_out_price['+iwarranty_nCisco_price+']').value=format(wprice);
    var nCisco = document.querySelectorAll(".m_price");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("m_total_out_price").value = format(nTotal);
    check_packages();
}
function maintenance_existing_backup(iwarranty_nCisco_price) {
    show_console("maintenance_existing_backup("+iwarranty_nCisco_price+")");
    wprice = document.getElementById("e_backup_price["+iwarranty_nCisco_price+"]").value; 
    document.getElementById('e_backup_price['+iwarranty_nCisco_price+']').value=format(wprice);
    var nCisco = document.querySelectorAll(".m_existing");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("e_total_backup_price").value = format(nTotal);
    check_packages();
}
function maintenance_investment_backup(iwarranty_nCisco_price) {
    show_console("maintenance_investment_backup("+iwarranty_nCisco_price+")");
    wprice = document.getElementById("inv_backup_price["+iwarranty_nCisco_price+"]").value; 
    document.getElementById('inv_backup_price['+iwarranty_nCisco_price+']').value=format(wprice);
    var nCisco = document.querySelectorAll(".inv_backup");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("inv_total_backup_price").value = format(nTotal);
    if(nTotal>0)
    { 
        document.getElementById("InvestmentBackup").style.display = "";
        // document.getElementById("BOQUpload").removeAttribute("disabled");
    } else
    {
        document.getElementById("InvestmentBackup").style.display = "none";
        // document.getElementById("BOQUpload").setAttribute("disabled", true);
    }
    check_packages();
}
function maintenance_addon(iwarranty_nCisco_price) {
    show_console("maintenance_addon("+iwarranty_nCisco_price+")");
    wprice = document.getElementById("m_pack_price["+iwarranty_nCisco_price+"]").value; 
    document.getElementById('m_pack_price['+iwarranty_nCisco_price+']').value=format(wprice);
    var nCisco = document.querySelectorAll(".m_addon");
    nTotal = 0;
    nCisco.forEach(function(input) {
        nTotal += deformat(input.value)*1;
    });
    document.getElementById("m_total_pack_price").value = format(nTotal);
    check_packages();
}

function agreed_show(axd)
{
    show_console("agreed_show("+axd+")");
    if(document.getElementById("i_bundling").checked)
    {
        document.getElementById("i_agreed_0").style.display = "";
        document.getElementById("i_agreed_1").style.display = "";
        document.getElementById("i_agreed_2").style.display = "";
        document.getElementById("i_agreed_3").style.display = "";
    } else
    {
        document.getElementById("i_agreed_0").style.display = "none";
        document.getElementById("i_agreed_1").style.display = "none";
        document.getElementById("i_agreed_2").style.display = "none";
        document.getElementById("i_agreed_3").style.display = "none";
    }
    if(document.getElementById("m_bundling").checked)
    {
        document.getElementById("m_agreed_0").style.display = "";
        document.getElementById("m_agreed_1").style.display = "";
        document.getElementById("m_agreed_2").style.display = "";
        document.getElementById("m_agreed_3").style.display = "";
    } else
    {
        document.getElementById("m_agreed_0").style.display = "none";
        document.getElementById("m_agreed_1").style.display = "none";
        document.getElementById("m_agreed_2").style.display = "none";
        document.getElementById("m_agreed_3").style.display = "none";
    }
    if(document.getElementById("i_bundling").checked || document.getElementById("m_bundling").checked)
    {
        document.getElementById("agreed_price").style.display = "";
    } else
    {
        document.getElementById("agreed_price").style.display = "none";
    }
}

function check_packages()
{
    show_console("check_packages()");
    const m_price = deformat(document.getElementById("m_price").value);
    const m_agreed_price = deformat(document.getElementById("m_agreed_price").value);
    const m_bpd_price = deformat(document.getElementById("m_bpd_price").value);
    const m_out_price = deformat(document.getElementById("m_total_out_price").value);
    const m_existing_backup = deformat(document.getElementById("e_total_backup_price").value);
    const m_investment_backup = deformat(document.getElementById("inv_total_backup_price").value);
    const m_addon_price = deformat(document.getElementById("m_total_pack_price").value);
    if(document.getElementById("band6").checked)
    {
        var mpackage = m_agreed_price*1-m_bpd_price*1-m_out_price*1-m_existing_backup*1-m_investment_backup*1;
        xasd = "1";
    } else
    {
        var mpackage = m_price*1-m_bpd_price*1-m_out_price*1-m_existing_backup*1-m_investment_backup*1;
         xasd = "2";
    }
    document.getElementById('m_package_price').value=format(mpackage);

    var mpackageother = mpackage-m_addon_price; 
    // alert(xasd+"\r\n"+mpackageother+"="+mpackage+"-"+m_addon_price+"\r\n"+"m_price : "+m_price+"\r\n"+"m_agreed_price : "+m_agreed_price+"\r\n"+"m_bpd_price : "+m_bpd_price+"\r\n"+"m_out_price : "+m_out_price+"\r\n"+"m_existing_backup : "+m_existing_backup+"\r\n"+"m_investment_backup : "+m_investment_backup);
    document.getElementById('m_package_other_price').value=format(mpackageother);
}

// Check Error
function check_error(axd) {
    show_console("check_error("+axd+")");
    var pesan_error='Data that needs to be corrected:<br/>';
    pesan_error+='<ul>';
    var pesan_tmp='';
    // var pesan_tmp1='';
    var sambung='';
    var status=false;

    if(document.getElementById("sbtype1").checked)
    {
        // SBTYPE IS PROJECT (sbtype1)
        // PROJECT INFORMATION
        const po_price = deformat(document.getElementById("amount_idr").value);
        const po_implementation = deformat(document.getElementById("i_price").value);
        const po_maintenance = deformat(document.getElementById("m_price").value);
        const po_warranty = deformat(document.getElementById("w_price").value);
        po_total = po_implementation*1 + po_maintenance*1 + po_warranty*1;

        // Setup Agreed Price List
        if(document.getElementById("i_bundling").checked)
        {
            if(deformat(document.getElementById("i_price_list").value)>0) {
                document.getElementById("i_price_list").style.backgroundColor = "";
            } else {
                pesan_tmp+='<li>Implementation Price List is not filled in.</li>';
                sambung=', ';
                status=true;
                document.getElementById("i_price_list").style.backgroundColor = "#FFC7CE";
            }
        }
        if(document.getElementById("m_bundling").checked)
        {
            if(deformat(document.getElementById("m_price_list").value)>0) {
                document.getElementById("m_price_list").style.backgroundColor = "";
            } else {
                pesan_tmp+='<li>Maintenance Price List is not filled in.</li>';
                sambung=', ';
                status=true;
                document.getElementById("m_price_list").style.backgroundColor = "#FFC7CE";
            }
        }
        // Check Agreed Price
        if(document.getElementById("band6").checked==true)
        {
            if(document.getElementById("i_bundling").checked)
            {
                if(deformat(document.getElementById("i_agreed_price").value)>0) {
                    document.getElementById("i_agreed_price").style.backgroundColor = "";
                } else {
                    pesan_tmp+='<li>Implementation Agreed Price is not filled in.</li>';
                    sambung=', ';
                    status=true;
                    document.getElementById("i_agreed_price").style.backgroundColor = "#FFC7CE";
                }
            }
            if(document.getElementById("m_bundling").checked)
            {
                if(deformat(document.getElementById("m_agreed_price").value)>0) {
                    document.getElementById("m_agreed_price").style.backgroundColor = "";
                } else {
                    pesan_tmp+='<li>Maintenance Agreed Price is not filled in.</li>';
                    sambung=', ';
                    status=true;
                    document.getElementById("m_agreed_price").style.backgroundColor = "#FFC7CE";
                }
            }
        }

    } else
    {
        // sbtype2
    }

    // Setup New Project
    if(document.getElementById("newproject1").checked || document.getElementById("newproject0").checked) {
        document.getElementById("newproject1").style.backgroundColor = "";
        document.getElementById("newproject0").style.backgroundColor = "";
    } else {
        pesan_tmp+='<li>Status Project has not been selected.</li>';
        // pesan_tmp1+=sambung+'Status Project has not been selected.';
        sambung=', ';
        status=true;
        document.getElementById("newproject1").style.backgroundColor = "#FFC7CE";
        document.getElementById("newproject0").style.backgroundColor = "#FFC7CE";
        pesan_tmp+='<li>Status Project has not been selected.</li>';
    }

    //Setup Type of Service Budget
    if(document.getElementById("sbtype0").checked || document.getElementById("sbtype1").checked || document.getElementById("sbtype2").checked) {
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
                        pesan_tmp+='<li>Estimation Project Duration is not filled in.</li>';
                        // pesan_tmp1+'Estimation Project Duration is not filled in';
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
                        pesan_tmp+='<li>Work Order type has not been selected.</li>';
                        // pesan_tmp1+=sambung+'Work Order type has not been selected.';
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
                pesan_tmp+='<li>Multiyears has not been selected.</li>';
                // pesan_tmp1+=sambung+'Multiyears has not been selected';
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
                pesan_tmp+='<li>Bundling Project has not been selected.</li>';
                // pesan_tmp1+=sambung+'Type Service Budget has not been selected';
                sambung=', ';
                status=true;
                document.getElementById("i_bundling").style.background="#FFC7CE";
                document.getElementById("m_bundling").style.background="#FFC7CE";
                document.getElementById("w_bundling").style.background="#FFC7CE";
            }
        
        }
    } else {
        pesan_tmp+='<li>Type Service Budget has not been selected.</li>';
        // pesan_tmp1+=sambung+'Type Service Budgete has not been selected';
        sambung=', ';
        status=true;
        document.getElementById("sbtype0").style.backgroundColor = "#FFC7CE";
        document.getElementById("sbtype1").style.backgroundColor = "#FFC7CE";
        document.getElementById("sbtype2").style.backgroundColor = "#FFC7CE";
    }

    // Check Presale Account and Technicall
    if(document.getElementById("sbtype1").checked)
    {
        if(document.getElementById("ps_account").value==0)
        {
            status = true;
            pesan_tmp+="<li>Pre-Sales Account has not been selected.</li>";
        }
        if(document.getElementById("psSelected").value=="")
        {
            status = true;
            pesan_tmp+="<li>Pre-Sales Technical has not been selected.</li>";
        }
    }

    if(pesan_tmp!='') {
        pesan_error+='<li>Project Information<ul>'+pesan_tmp+'</ul></li>';
        document.getElementById("info-tab").style.color='red';
    } else
    {
        document.getElementById("info-tab").style.color='#333';
    }

    // SOLUTION
    pesan_tmp='';
    // pesan_tmp1='';
    sambung='';
    // if(document.getElementById("s_total_product").value!=100)
    // {
    //     pesan_tmp += '<li>Total Product Solution must be 100%.</li>';
    //     // pesan_tmp1 += 'Total product solution must be 100%.;';
    //     status = true;
    // }
    if(document.getElementById("sbtype1").checked)
    {
        if(document.getElementById("s_total_product").value==100 && document.getElementById("s_total_service").value==0 || document.getElementById("s_total_product").value==0 && document.getElementById("s_total_service").value==100 || document.getElementById("s_total_product").value==100 && document.getElementById("s_total_service").value==100)
        {} else
        {
            pesan_tmp += '<li>Total Product and/or Service Solution must be 100%.</li>';
            // pesan_tmp1 += 'Total solution solution must be 100%.;';
            status = true;
        }
    } else
    if(document.getElementById("sbtype0").checked || document.getElementById("sbtype2").checked)
    {
        if(document.getElementById("s_total_product").value!=100 && document.getElementById("s_total_product").value!=0)
        {
            pesan_tmp += '<li>Total Product Service Solution must be 100%.</li>';
        }
    }

    if(pesan_tmp!='') {
        pesan_error+='<li>Project Solution<ul>'+pesan_tmp+'</ul></li>';
    }

    if(document.getElementById("sbtype1").checked) 
    {
        pesan_tmp='';
        // IMPLEMENTATION
        // pesan_tmp1='';
        sambung='';
        if(document.getElementById('i_bundling').checked) {
            // check Service Catalog
            if(document.getElementById('i_tos_id0').checked || document.getElementById('i_tos_id1').checked || document.getElementById('i_tos_id2').checked) {
                document.getElementById("i_tos_id0").style.backgroundColor ="";
                document.getElementById("i_tos_id1").style.backgroundColor ="";
                document.getElementById("i_tos_id2").style.backgroundColor ="";
                // document.getElementById("implementation-tab").style.color='#333';
            } else {
                pesan_tmp += '<li>Service Type has not been selected.</li>';
                // pesan_tmp1+='Service Type has not been selected';
                sambung=', ';
                status=true;
                document.getElementById("i_tos_id0").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_tos_id1").style.backgroundColor ="#FFC7CE";
                document.getElementById("i_tos_id2").style.backgroundColor ="#FFC7CE";
                // document.getElementById("implementation-tab").style.color='red';
            }

            // Check Implementation Project Estimation
            if(document.getElementById("i_project_estimation").value==0) {
                pesan_tmp += '<li>Estimation Project Duration is not filled in.</li>';
                // pesan_tmp1+=sambung+'Estimation Project Duration is not filled in';
                sambung=', ';
                status=true;
                document.getElementById("i_project_estimation").style.backgroundColor ="#FFC7CE";
                // document.getElementById("implementation-tab").style.color='red';
            } else {
                document.getElementById("i_project_estimation").style.backgroundColor ="";
                // document.getElementById("implementation-tab").style.color='#333';
            }

            // Check Outsourcing
            var inputs = document.querySelectorAll('.i_price');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("i_out_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Outsorucing :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }

            // Check Mandays
            // if(document.getElementById("mandays[0]['type1']").checked || document.getElementById("mandays[0]['type2']").checked || document.getElementById("mandays[0]['type3']").checked)
            // {
            //     document.getElementById("mandays[0]['type1']").style.backgroundColor = "";
            //     document.getElementById("mandays[0]['type2']").style.backgroundColor = "";
            //     document.getElementById("mandays[0]['type3']").style.backgroundColor = "";
            // } else
            // {
            //     pesan_tmp += '<li>Mandays Type is not selected.</li>';
            //     status = true;
            //     document.getElementById("mandays[0]['type1']").style.backgroundColor = "#FFC7CE";
            //     document.getElementById("mandays[0]['type2']").style.backgroundColor = "#FFC7CE";
            //     document.getElementById("mandays[0]['type3']").style.backgroundColor = "#FFC7CE";
            // }
            if(typeof document.getElementById("mandays[0]['type1']") !== 'undefined' || typeof document.getElementById("mandays[0]['type2']") !== 'undefined' || typeof document.getElementById("mandays[0]['type3']") !== 'undefined')
            {
                show_console("Mandays Type undefiend");
            } else
            {
                show_console("Mandays Type defined");
                if(document.getElementById("mandays[0]['type1']").checked || document.getElementById("mandays[0]['type2']").checked || document.getElementById("mandays[0]['type3']").checked)
                {
                    document.getElementById("mandays[0]['type1']").style.backgroundColor = "";
                    document.getElementById("mandays[0]['type2']").style.backgroundColor = "";
                    document.getElementById("mandays[0]['type3']").style.backgroundColor = "";
                } else
                {
                    pesan_tmp += '<li>Mandays Type is not selected.</li>';
                    status = true;
                    document.getElementById("mandays[0]['type1']").style.backgroundColor = "#FFC7CE";
                    document.getElementById("mandays[0]['type2']").style.backgroundColor = "#FFC7CE";
                    document.getElementById("mandays[0]['type3']").style.backgroundColor = "#FFC7CE";
                }
            }

            // status=true;
            // pesan_tmp += '<li>Mandays Calculation is not filled in. <span class="text-danger fw-bold">(on developement)</span></li>';
            var total_mandays = deformat(document.getElementById("total_mandays").value);
            if(total_mandays<0)
            {
                status=true;
                if(document.getElementById("band6").checked)
                {
                    var iprice = document.getElementById("i_agreed_price").value;
                    var ipricetext = "Agreed Price";
                } else
                {
                    var iprice = document.getElementById("i_price_list").value;
                    var ipricetext = "Price List";
                }
                pesan_tmp+='<li>Mandays Value is less than zero<ul>';
                pesan_tmp+='<table class="table-sm">';
                pesan_tmp+='<tr><td>'+ipricetext+'</td><td class="text-right">'+iprice+'</td></tr>';
                pesan_tmp+='<tr><td>Business Trip</td><td class="text-right">-'+document.getElementById("i_bpd_price").value+'</td></tr>';
                pesan_tmp+='<tr><td>Outsourcing</td><td class="text-right">-'+document.getElementById("i_total_out_price").value+'</td></tr>';
                pesan_tmp+='<tr class="border-top border-secondary fw-bold"><td>Total Mandays</td><td class="text-right">'+document.getElementById("total_mandays").value+'</td></tr>';
                pesan_tmp+='</table>';
                pesan_tmp+='</ul></li>';
                document.getElementById("total_mandays").style.backgroundColor="#FFC7CE";
            } else
            {
                document.getElementById("total_mandays").style.backgroundColor="";
            }
            // var inputs = document.getElementById("mandays");
            // i = 0;
            // pesan_out = ""
            // inputs.forEach(function(input) {
            //     pesan_tmp+='<li>'+input[i]['PDMans'].value+'</li>';
            // });
            // pesan_tmp1 += 'Mandays calculation is not filled in.';


            if(pesan_tmp!='') {
                pesan_error+='<li>Implementation :<ul>'+pesan_tmp+'</ul></li>';
                document.getElementById("implementation-tab").style.color='red';
            } else
            {
                document.getElementById("implementation-tab").style.color='#333';
            }
        } else
        {
            document.getElementById("implementation-tab").style.display = "none";
        }
        
        // MAINTENANCE
        pesan_tmp='';
        // pesan_tmp1='';
        sambung='';
        if(document.getElementById('m_bundling').checked) {
            // Check Service Catalog.
            if(document.getElementById("m_tos_id0").checked==false && document.getElementById("m_tos_id1").checked==false && document.getElementById("m_tos_id2").checked==false && document.getElementById("m_tos_id3").checked==false && document.getElementById("m_tos_id4").checked==false)
            {
                pesan_tmp += "<li>Service Type has not been selected.</li>";
                status = true;
                document.getElementById("m_tos_id0").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id1").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id2").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id3").style.backgroundColor ="#FFC7CE";
                document.getElementById("m_tos_id4").style.backgroundColor ="#FFC7CE";
            } else
            {
                document.getElementById("m_tos_id0").style.backgroundColor ="";
                document.getElementById("m_tos_id1").style.backgroundColor ="";
                document.getElementById("m_tos_id2").style.backgroundColor ="";
                document.getElementById("m_tos_id3").style.backgroundColor ="";
                document.getElementById("m_tos_id4").style.backgroundColor ="";
            }
            if(document.getElementById("addon_reporting").value=="")
            {
                pesan_tmp+='<li>Total Maintenance Report is not filled in.</li>';
                document.getElementById("addon_reporting").style.backgroundColor = "#FFC7CE";
            } else
            {
                document.getElementById("addon_reporting").style.backgroundColor = "";
            }
            if(document.getElementById("addon_preventive").value=="")
            {
                pesan_tmp+='<li>Total Preventive Maintenance Report is not filled in.</li>';
                document.getElementById("addon_preventive").style.backgroundColor ="#FFC7CE";
            } else
            {
                document.getElementById("addon_preventive").style.backgroundColor ="";
            }
            if(document.getElementById("addon_ticket").value=="")
            {
                pesan_tmp+='<li>Total Add-Move-Change Ticket is not filled in.</li>';
                document.getElementById("addon_ticket").style.backgroundColor = "#FFC7CE";
            } else
            {
                document.getElementById("addon_ticket").style.backgroundColor ="";
            }
            // Check Maintenance Project Estimation
            if(document.getElementById("m_project_estimation").value==0) {
                pesan_tmp += '<li>Estimation Project Duration is zero.</li>';
                // pesan_tmp1+=sambung+'Estimation Project Duration is zero';
                sambung=', ';
                status=true;
                document.getElementById("m_project_estimation").style.backgroundColor ="#FFC7CE";
            } else {
                document.getElementById("m_project_estimation").style.backgroundColor ="";
            }

            // Check PO Customer untuk Maintenance
            if(document.getElementById("m_price").value==0) {
                pesan_tmp += '<li>Maintenance Price (sesuai PO/SPK) is zero.</li>';
                // pesan_tmp1+=sambung+'Maintenance Price (sesuai PO/SPK) is zero';
                status=true;
                document.getElementById("m_price").style.backgroundColor ="#FFC7CE";
            } else {
                document.getElementById("m_price").style.backgroundColor ="";
            }
            // Check Maintenance Oursourcing Plan
            var inputs = document.querySelectorAll('.m_price');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("m_out_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                    status = true;
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Outsourcing :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }
            // Check Maintenance Addon
            var inputs = document.querySelectorAll('.m_addon');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("m_pack_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                    status = true;
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Addon :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }
            // Check Existing Backup Unit
            var inputs = document.querySelectorAll('.m_existing');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("e_backup_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                    status = true;
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Existing Backup Unit :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }
            // Check Investment Backup Unit
            var inputs = document.querySelectorAll('.inv_backup');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("inv_backup_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Investment Backup Unit :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }
            // Checked Dedicate Backup Unit
            if(deformat(document.getElementById("inv_total_backup_price").value)>0)
            {
                document.getElementById("InvestmentBackup").style.display = "";
                // document.getElementById("BOQUpload").removeAttribute("disabled");
                if(document.getElementById("dedicate_backup1").checked==false && document.getElementById("dedicate_backup2").checked==false)
                {
                    pesan_tmp += '<li>Dedicate Backup Unit has not been selected.</li>';
                    status=true;
                    document.getElementById("dedicate_backup1").style.backgroundColor ="#FFC7CE";
                    document.getElementById("dedicate_backup2").style.backgroundColor ="#FFC7CE";
                    // document.getElementById("maintenance-tab").style.color='red';
                } else {
                    document.getElementById("dedicate_backup1").style.backgroundColor ="";
                    document.getElementById("dedicate_backup2").style.backgroundColor ="";
                    // document.getElementById("maintenance-tab").style.color='#333';
                }
            } else
            {
                document.getElementById("InvestmentBackup").style.display = "none";
                // document.getElementById("BOQUpload").setAttribute("disabled", true);
            }
            // Check file BOQ is uploaded
            if(document.getElementById("fileName").value=="")
            {
                pesan_tmp += '<li>Bill Of Material file has not been uploaded.</li>';
                status = true;
            }
            // Check Addon
            const m_price = deformat(document.getElementById("m_price").value);
            const m_agreed_price = deformat(document.getElementById("m_agreed_price").value);
            const m_bpd_price = deformat(document.getElementById("m_bpd_price").value);
            const m_out_price = deformat(document.getElementById("m_total_out_price").value);
            const m_existing_backup = deformat(document.getElementById("e_total_backup_price").value);
            const m_investment_backup = deformat(document.getElementById("inv_total_backup_price").value);
            const m_addon_price = deformat(document.getElementById("m_total_pack_price").value);
            const m_services = m_bpd_price*1+m_out_price*1+m_existing_backup*1+m_investment_backup*1;
            if(document.getElementById("band6").checked)
            {
                var mpackage = m_agreed_price*1-m_bpd_price*1-m_out_price*1-m_existing_backup*1-m_investment_backup*1;
                var m_price_x = m_agreed_price;
                var mpackagedesc = "Agreed Price";
            } else
            {
                var mpackage = m_price*1-m_bpd_price*1-m_out_price*1-m_existing_backup*1-m_investment_backup*1;
                var m_price_x = m_price;
                var mpackagedesc = "Maintenance Price";
            }
            document.getElementById('m_package_price').value=format(mpackage);
            if(m_price_x==0)
            {
                pesan_tmp+='<li>'+mpackagedesc+' is zero.</li>';
            }

            var mpackageother = mpackage-m_addon_price;
            document.getElementById('m_package_other_price').value=format(mpackageother);
            check_packages();
            if(mpackage<0 || mpackageother<0)
            {
                pesan_tmp+='<li>Maintenance Package<br/>';
                pesan_tmp+='<table class="table-sm">';
                pesan_tmp+='<tr><td>Maintenance Value</td><td class="text-right">'+format(m_price_x,2)+'</td></tr>';
                pesan_tmp+='<tr><td>Business Trip</td><td class="text-right">-'+format(m_bpd_price,2)+'</td></tr>';
                pesan_tmp+='<tr><td>Outsourcing</td><td class="text-right">-'+format(m_out_price,2)+'</td></tr>';
                pesan_tmp+='<tr><td>Existing Backup Unit</td><td class="text-right">-'+format(m_existing_backup,2)+'</td></tr>';
                pesan_tmp+='<tr><td>Investment Backup Unit</td><td class="text-right">-'+format(m_investment_backup,2)+'</td></tr>';
                pesan_tmp+='<tr class="fw-bold border-top border-secondary"><td>Maintenance Package</td><td class="text-right">'+format(mpackage,2)+'</td></tr>';
                pesan_tmp+='<tr><td>Addon</td><td class="text-right">-'+format(m_addon_price,2)+'</td></tr>';
                pesan_tmp+='<tr class="fw-bold border-top border-secondary"><td>Other (Non-Addon)</td><td class="text-right">'+format(mpackageother,2)+'</td></tr>';
                pesan_tmp+='</table>';
                pesan_tmp+='</li>';
            }

            if(pesan_tmp!="")
            {
                pesan_error+='<li>Maintenance :<ul>'+pesan_tmp+'</ul></li>';
                document.getElementById('maintenance-tab').style.color="#ee0000";
                status = true;
            } else
            {
                document.getElementById('maintenance-tab').style.color="#333";
            }
        } else
        {
            document.getElementById("maintenance-tab").style.display = "none";
        }
        
    
        // EXTENDED WARRANTY
        pesan_tmp='';
        // pesan_tmp1='';
        sambung='';
        // Check Estimation Warranty Duration
        if(document.getElementById('w_bundling').checked) 
        {
            if(document.getElementById("w_project_estimation").value==0)
            {
                document.getElementById("w_project_estimation").style.backgroundColor = "#FFC7CE";
                pesan_tmp += '<li>Project Estimate is not filled in.</li>';
                // pesan_tmp1 += 'Project Estimate is not filled in.;';
            } else
            {
                document.getElementById("w_project_estimation").style.backgroundColor = "";
            }

            // Check Warranty Addon
            var inputs = document.querySelectorAll('.warranty_addon');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("warranty_addon_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Addon :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }
            // Check Non-Cisco Product
            var inputs = document.querySelectorAll('.non_cisco');
            i = 0;
            pesan_out = "";
            inputs.forEach(function(input) {
                var i_title = document.getElementById("warranty_nCisco_title["+i+"]")
                var value = input.value;
                if(value==0)
                {
                    pesan_out+='<li>'+i_title.value+' is null or zero.</li>';
                    input.style.backgroundColor ="#FFC7CE";
                } else
                {
                    input.style.backgroundColor ="";
                }
                i++;
            });
            if(pesan_out!="")
            {
                pesan_tmp+='<li>Non-Cisco :<ul>';
                pesan_tmp+=pesan_out;
                pesan_tmp+='</ul></li>';
            }
            // // Check Warranty Value
            // const WValue = deformat(document.getElementById("w_price").value);
            // const WAddon = deformat(document.getElementById("warranty_addon_price").value);
            // const WCisco = deformat(document.getElementById("warranty_Cisco_price[0]").value);
            // const WNCisco = deformat(document.getElementById("warranty_nCisco_price").value);
            // const WTotal = WAddon*1 + WCisco*1 + WNCisco*1;
            // if(WValue!=WTotal)
            // {
            //     pesan_tmp+='<li>The Extended Warranty Value is not the same as the Total Services.<br/>';
            //     pesan_tmp+='<table class="table-sm">';
            //     pesan_tmp+='<tr><td>Addon</td><td class="text-right">'+format(WAddon,2)+'</td></tr>';
            //     pesan_tmp+='<tr><td>Cisco</td><td class="text-right">'+format(WCisco,2)+'</td></tr>';
            //     pesan_tmp+='<tr class="border-bottom border-secondary"><td>Non-Cisco</td><td class="text-right">'+format(WNCisco,2)+'</td></tr>';
            //     pesan_tmp+='<tr class="fw-bold"><td>Total Services</td><td class="text-right">'+format(WTotal,2)+'<td></tr>';
            //     pesan_tmp+='<tr class="fw-bold"><td>Extended Warranty Value</td><td class="fw-bold text-right">'+format(WValue,2)+'</td></tr>';
            //     pesan_tmp+='</table>';
            //     pesan_tmp+='</li>';
            //     document.getElementById('w_price').style.backgroundColor="#FFC7CE";
            //     document.getElementById('warranty_addon_price').style.backgroundColor="#FFC7CE";
            //     document.getElementById('warranty_Cisco_price[0]').style.backgroundColor="#FFC7CE";
            //     document.getElementById('warranty_nCisco_price').style.backgroundColor="#FFC7CE";
            //     document.getElementById('warranty-tab').style.color="#ee0000";
            // } else
            // {
            //     document.getElementById('w_price').style.backgroundColor="";
            //     document.getElementById('warranty_addon_price').style.backgroundColor="";
            //     document.getElementById('warranty_Cisco_price[0]').style.backgroundColor="";
            //     document.getElementById('warranty_nCisco_price').style.backgroundColor="";
            //     document.getElementById('warranty-tab').style.color="#333";
            // }
            // Check Extended Warranty Price (sesuai PO/SPK)
            if(document.getElementById("w_price").value==0)
            {
                document.getElementById("w_price").style.backgroundColor = "#FFC7CE";
                pesan_tmp += '<li>Extended Warranty Price (sesuai PO/SPK) is zero.</li>';
                // pesan_tmp1 += 'Extended Warranty Price (sesuai PO/SPK) is not filled in.;';
            } else
            {
                document.getElementById("w_price").style.backgroundColor = "";
            }
            if(document.getElementById("w_tos_id0").checked==false && document.getElementById("w_tos_id1").checked==false)
            {
                pesan_tmp+='<li>Service Type has not selected.</li>';
                document.getElementById("w_tos_id0").style.backgroundColor = "#FFC7CE";
                document.getElementById("w_tos_id1").style.backgroundColor = "#FFC7CE";
            } else
            {
                document.getElementById("w_tos_id0").style.backgroundColor = "";
                document.getElementById("w_tos_id1").style.backgroundColor = "";
            }
    
            if(pesan_tmp!="")
            {
                pesan_error+='<li>Extended Warranty<ul>'+pesan_tmp+'</ul></li>';
                document.getElementById('warranty-tab').style.color="#ee0000";
                status = true;
            } else
            {
                document.getElementById('warranty-tab').style.color="#333";
            }
        } else
        {
            document.getElementById("warranty-tab").style.display = "none";
        }
        document.getElementById("sub-solution").style.display = "";

    } else
    {
        document.getElementById("sub-solution").style.display = "none";
    }

    pesan_error+="</ul>";

    // Tampilkan pesan
    if(status==false) {
        document.getElementById("submit_service_budget").removeAttribute("disabled");
        document.getElementById("submit_pesan_error").style.display='none';
        document.getElementById("save_pesan_error").innerHTML='<p>Data is complete. Please save and submit approval.</p>';
        document.getElementById("save_pesan_error").style.display='';
        document.getElementById("note_submited").style.display='';
        document.getElementById('note_save').innerHTML='The data has been completed.';
        // if(confirm_mandays==true) {
        //     var pesannya = "<span class='text-danger'>Pastikan Mandays Calculation memang kosong.</span>";
        //     document.getElementById("save_pesan_confirm").innerHTML= pesannya;
        //     document.getElementById("save_pesan_confirm").style.display="";
        // } else {
        //     document.getElementById("save_pesan_confirm").style.display="none";
        // }

    } else {
        document.getElementById('submit_pesan_error').innerHTML='<div class="text-danger"><span class="fw-bold">Minta dilengkapi data;</span>'+pesan_error+'</div>';
        document.getElementById('save_pesan_error').innerHTML='<p class="text-danger fw-bold">The data you entered is incomplete!!!</p><p>Insufficient data is marked in red in the field.</p>'+pesan_error;
        document.getElementById('note_save').innerHTML='<br/>'+pesan_error;
        document.getElementById("submit_service_budget").setAttribute("disabled", true);
        document.getElementById("submit_pesan_error").style.display='';
        document.getElementById("save_pesan_error").style.display='';
        document.getElementById("note_submited").style.display='none';
    }
}

function check_acknowledge(axd)
{
    show_console("check_acknowledge("+axd+")");
    var inputs = document.querySelectorAll('.SubSolution');
    CheckStatus = false;
    inputs.forEach(function(input) {
        if(input.checked)
        {
            CheckStatus = true;
        }
    });
    show_console(CheckStatus);

    if(CheckStatus)
    {
        // document.getElementById("ack_failed").innerHTML = "PASS";
        document.getElementById("ack_pass").style.display = "";
        document.getElementById("ack_failed").style.display = "none";
    } else
    {
        $msg = '<p class="text-danger fw-bold">You must select one or more Sub-Solution options on the Project Solution tab.</p>';
        document.getElementById("ack_failed").innerHTML = $msg;
        document.getElementById("ack_pass").style.display = "none";
        document.getElementById("ack_failed").style.display = "";
    }
}

function change_mtos()
{
    show_console("change_mtos()");
    if(document.getElementById("m_tos_id0").checked)
    {
        var reporting = document.getElementById("TosM['Gold']['reporting']").value;
        var preventive = document.getElementById("TosM['Gold']['preventive']").value;
        var ticket = document.getElementById("TosM['Gold']['ticket']").value;
        document.getElementById("plan_reporting").value = reporting;
        document.getElementById("plan_preventive").value = preventive;
        document.getElementById("plan_ticket").value = ticket;
    } else
    if(document.getElementById("m_tos_id1").checked)
    {
        var reporting = document.getElementById("TosM['Silver']['reporting']").value;
        var preventive = document.getElementById("TosM['Silver']['preventive']").value;
        var ticket = document.getElementById("TosM['Silver']['ticket']").value;
        document.getElementById("plan_reporting").value = reporting;
        document.getElementById("plan_preventive").value = preventive;
        document.getElementById("plan_ticket").value = ticket;
    } else
    if(document.getElementById("m_tos_id2").checked)
    {
        var reporting = document.getElementById("TosM['Bronze']['reporting']").value;
        var preventive = document.getElementById("TosM['Bronze']['preventive']").value;
        var ticket = document.getElementById("TosM['Bronze']['ticket']").value;
        document.getElementById("plan_reporting").value = reporting;
        document.getElementById("plan_preventive").value = preventive;
        document.getElementById("plan_ticket").value = ticket;
    } else
    {
        var reporting = 0;
        var preventive = 0;
        var ticket = 0;
        document.getElementById("plan_reporting").value = reporting;
        document.getElementById("plan_preventive").value = preventive;
        document.getElementById("plan_ticket").value = ticket;
    }
    var addonReporting = document.getElementById("addon_reporting").value;
    var addonPreventive = document.getElementById("addon_preventive").value;
    var addonTicket = document.getElementById("addon_ticket").value;
    document.getElementById("total_reporting").value = reporting*1+addonReporting*1;
    document.getElementById("total_preventive").value = preventive*1+addonPreventive*1;
    document.getElementById("total_ticket").value = ticket*1+addonTicket*1;
}