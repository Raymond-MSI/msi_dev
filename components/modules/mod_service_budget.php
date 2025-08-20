<?php
if (!isset($_GET['sub'])) {
    $mdlname = "SERVICE_BUDGET";
    $userpermission = useraccess_v2($mdlname);
    $modulename = "Service Budget";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION_V2 == "bf7717bbfd879cd1a40b71171f9b393e") {
        // if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="726ea0dd998698e8a87f8e344d373533" || USERPERMISSION=="5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION=="335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION=="0162bce636a63c3ae499224203e06ed0" || USERPERMISSION=="858ba4765e53c712ef672a9570474b1d" ) {
?>
        <input type="hidden" id="user_name" value="<?php echo $_SESSION['Microservices_UserName']; ?>">
        <script>
            $(document).ready(function() {
                // SB Draft
                var tableUnApproved = $('#view_project_list').DataTable({
                    dom: 'Blrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa fa-circle-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=order_list";
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Sales Order'><i class='fa fa-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=project_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit SB'><i class='fa fa-pen'></i></span>",
                            action: function() {
                                var rownumber = tableUnApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableUnApproved.cell(rownumber, 1).data();
                                var so_number = tableUnApproved.cell(rownumber, 2).data();
                                var order_number = tableUnApproved.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=edit&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View SB'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = tableUnApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableUnApproved.cell(rownumber, 1).data();
                                var so_number = tableUnApproved.cell(rownumber, 2).data();
                                var order_number = tableUnApproved.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=view&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span  class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Copy SB'><i class='fa fa-copy'></i></span>",
                            action: function() {
                                var rownumber = tableUnApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableUnApproved.cell(rownumber, 1).data();
                                var so_number = tableUnApproved.cell(rownumber, 2).data();
                                var order_number = tableUnApproved.cell(rownumber, 3).data();
                                document.getElementById("project_code").value = project_code;
                                document.getElementById("so_number").value = so_number;
                                document.getElementById("order_number").value = order_number;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=copy&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export PDF'><i class='fa fa-file-pdf'></i></span>",
                            action: function() {
                                var rownumber = tableUnApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableUnApproved.cell(rownumber, 1).data();
                                var so_number = tableUnApproved.cell(rownumber, 2).data();
                                var order_number = tableUnApproved.cell(rownumber, 3).data();
                                var user_name = document.getElementById("user_name").value;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    // window.location.href = "components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V2.php?project_code="+project_code+"&so_number="+so_number+"&order_number="+order_number+"&user="+user_name;
                                    window.open("components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V3.php?project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&user=" + user_name, "_blank");
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='All My Service Budget'><i class='fa fa-database'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&act=all";
                            },
                            enabled: false
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filter" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='List Backup BOQ'><i class='fa-solid fa-cart-shopping'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&sub=list_boq";
                            },
                            enabled: true
                        },
                    ],
                    "columnDefs": [{
                            "targets": [0, 1, 2, 3, 8, 10, 11, 12, 13, 14, 15, 16, 17],
                            "visible": false
                        },
                        {
                            "targets": [8, 9],
                            "className": 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number('.', ',', 2, '').display(data);

                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        },
                    ],
                    "order": [
                        [17, "desc"]
                    ]
                });

                // SB Submited
                var tableSubmit = $('#view_project_list1').DataTable({
                    dom: 'Blrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa fa-circle-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=order_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB'><i class='fa fa-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=project_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit SB'><i class='fa fa-pen'></i></span>",
                            action: function() {
                                var rownumber = tableSubmit.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableSubmit.cell(rownumber, 1).data();
                                var so_number = tableSubmit.cell(rownumber, 2).data();
                                var order_number = tableSubmit.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=edit&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Submit";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View SB'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = tableSubmit.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableSubmit.cell(rownumber, 1).data();
                                var so_number = tableSubmit.cell(rownumber, 2).data();
                                var order_number = tableSubmit.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=view&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Submit";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span  class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Copy SB'><i class='fa fa-copy'></i></span>",
                            action: function() {
                                var rownumber = tableSubmit.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableSubmit.cell(rownumber, 1).data();
                                var so_number = tableSubmit.cell(rownumber, 2).data();
                                var order_number = tableSubmit.cell(rownumber, 3).data();
                                document.getElementById("project_code").value = project_code;
                                document.getElementById("so_number").value = so_number;
                                document.getElementById("order_number").value = order_number;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=copy&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export PDF'><i class='fa fa-file-pdf'></i></span>",
                            action: function() {
                                var rownumber = tableSubmit.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableSubmit.cell(rownumber, 1).data();
                                var so_number = tableSubmit.cell(rownumber, 2).data();
                                var order_number = tableSubmit.cell(rownumber, 3).data();
                                var user_name = document.getElementById("user_name").value;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    // window.location.href = "components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V2.php?project_code="+project_code+"&so_number="+so_number+"&order_number="+order_number+"&user="+user_name;
                                    window.open("components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V3.php?project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&user=" + user_name, "_blank");
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='All My Service Budget'><i class='fa fa-database'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&act=all";
                            },
                            enabled: false
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filter" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='List Backup BOQ'><i class='fa-solid fa-cart-shopping'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&sub=list_boq";
                            },
                            enabled: true
                        },
                    ],
                    "columnDefs": [{
                            "targets": [0, 1, 2, 3, 8, 10, 11, 12, 13, 14, 15, 16, 17],
                            "visible": false
                        },
                        {
                            "targets": [8, 9],
                            "className": 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number('.', ',', 2, '').display(data);

                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        }
                    ],
                    "order": [
                        [17, "DES"]
                    ]
                });

                // SB Approved Trade
                var tableApprovedTrade = $('#view_project_list2').DataTable({
                    dom: 'Blrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa fa-circle-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=order_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB'><i class='fa fa-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=project_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit SB'><i class='fa fa-pen'></i></span>",
                            action: function() {
                                var rownumber = tableApprovedTrade.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApprovedTrade.cell(rownumber, 1).data();
                                var so_number = tableApprovedTrade.cell(rownumber, 2).data();
                                var order_number = tableApprovedTrade.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=edit&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved0";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View SB'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = tableApprovedTrade.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApprovedTrade.cell(rownumber, 1).data();
                                var so_number = tableApprovedTrade.cell(rownumber, 2).data();
                                var order_number = tableApprovedTrade.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=view&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved0";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span  class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Copy SB'><i class='fa fa-copy'></i></span>",
                            action: function() {
                                var rownumber = tableApprovedTrade.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApprovedTrade.cell(rownumber, 1).data();
                                var so_number = tableApprovedTrade.cell(rownumber, 2).data();
                                var order_number = tableApprovedTrade.cell(rownumber, 3).data();
                                document.getElementById("project_code").value = project_code;
                                document.getElementById("so_number").value = so_number;
                                document.getElementById("order_number").value = order_number;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=copy&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export PDF'><i class='fa fa-file-pdf'></i></span>",
                            action: function() {
                                var rownumber = tableApprovedTrade.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApprovedTrade.cell(rownumber, 1).data();
                                var so_number = tableApprovedTrade.cell(rownumber, 2).data();
                                var order_number = tableApprovedTrade.cell(rownumber, 3).data();
                                var user_name = document.getElementById("user_name").value;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    // window.location.href = "components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V2.php?project_code="+project_code+"&so_number="+so_number+"&order_number="+order_number+"&user="+user_name;
                                    window.open("components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V3.php?project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&user=" + user_name, "_blank");
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='All My Service Budget'><i class='fa fa-database'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&act=all";
                            },
                            enabled: false
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filter" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa-solid fa-cart-shopping'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&sub=list_boq";
                            },
                            enabled: true
                        },
                    ],
                    "columnDefs": [{
                            "targets": [0, 1, 2, 3, 8, 10, 11, 12, 13, 14, 15, 16, 17],
                            "visible": false
                        },
                        {
                            "targets": [8, 9],
                            "className": 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number('.', ',', 2, '').display(data);

                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        }
                    ],
                    "order": [
                        [17, "DES"]
                    ]
                });

                // SB Approved Non-Trade
                var tableApproved = $('#view_project_list21').DataTable({
                    dom: 'Blrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa fa-circle-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=order_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB'><i class='fa fa-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=project_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit SB'><i class='fa fa-pen'></i></span>",
                            action: function() {
                                var rownumber = tableApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApproved.cell(rownumber, 1).data();
                                var so_number = tableApproved.cell(rownumber, 2).data();
                                var order_number = tableApproved.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=edit&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved0";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View SB'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = tableApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApproved.cell(rownumber, 1).data();
                                var so_number = tableApproved.cell(rownumber, 2).data();
                                var order_number = tableApproved.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=view&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved0";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span  class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Copy SB'><i class='fa fa-copy'></i></span>",
                            action: function() {
                                var rownumber = tableApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApproved.cell(rownumber, 1).data();
                                var so_number = tableApproved.cell(rownumber, 2).data();
                                var order_number = tableApproved.cell(rownumber, 3).data();
                                document.getElementById("project_code").value = project_code;
                                document.getElementById("so_number").value = so_number;
                                document.getElementById("order_number").value = order_number;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=copy&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export PDF'><i class='fa fa-file-pdf'></i></span>",
                            action: function() {
                                var rownumber = tableApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableApproved.cell(rownumber, 1).data();
                                var so_number = tableApproved.cell(rownumber, 2).data();
                                var order_number = tableApproved.cell(rownumber, 3).data();
                                var user_name = document.getElementById("user_name").value;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    // window.location.href = "components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V2.php?project_code="+project_code+"&so_number="+so_number+"&order_number="+order_number+"&user="+user_name;
                                    window.open("components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V3.php?project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&user=" + user_name, "_blank");
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='All My Service Budget'><i class='fa fa-database'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&act=all";
                            },
                            enabled: false
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filter" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa-solid fa-cart-shopping'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&sub=list_boq";
                            },
                            enabled: true
                        },
                    ],
                    "columnDefs": [{
                            "targets": [0, 1, 2, 3, 8, 10, 11, 12, 13, 14, 15, 16, 17],
                            "visible": false
                        },
                        {
                            "targets": [8, 9],
                            "className": 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number('.', ',', 2, '').display(data);

                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        }
                    ],
                    "order": [
                        [17, "DES"]
                    ]
                });

                // SB Temp
                var tableTempApproved = $('#view_project_list3').DataTable({
                    dom: 'Blrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa fa-circle-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=order_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB'><i class='fa fa-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=project_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit SB'><i class='fa fa-pen'></i></span>",
                            action: function() {
                                var rownumber = tableTempApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableTempApproved.cell(rownumber, 1).data();
                                var so_number = tableTempApproved.cell(rownumber, 2).data();
                                alert(project_code);
                                var order_number = tableTempApproved.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=edit&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View SB'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = tableTempApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableTempApproved.cell(rownumber, 1).data();
                                var so_number = tableTempApproved.cell(rownumber, 2).data();
                                var order_number = tableTempApproved.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=view&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span  class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Copy SB'><i class='fa fa-copy'></i></span>",
                            action: function() {
                                var rownumber = tableTempApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableTempApproved.cell(rownumber, 1).data();
                                var so_number = tableTempApproved.cell(rownumber, 2).data();
                                var order_number = tableApproved.cell(rownumber, 3).data();
                                document.getElementById("project_code").value = project_code;
                                document.getElementById("so_number").value = so_number;
                                document.getElementById("order_number").value = order_number;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=copy&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export PDF'><i class='fa fa-file-pdf'></i></span>",
                            action: function() {
                                var rownumber = tableTempApproved.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableTempApproved.cell(rownumber, 1).data();
                                var so_number = tableTempApproved.cell(rownumber, 2).data();
                                var order_number = tableTempApproved.cell(rownumber, 3).data();
                                var user_name = document.getElementById("user_name").value;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    // window.location.href = "components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V2.php?project_code="+project_code+"&so_number="+so_number+"&order_number="+order_number+"&user="+user_name;
                                    window.open("components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V3.php?project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&user=" + user_name, "_blank");
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='All My Service Budget'><i class='fa fa-database'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&act=all";
                            },
                            enabled: false
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filter" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa-solid fa-cart-shopping'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&sub=list_boq";
                            },
                            enabled: true
                        },
                    ],
                    "columnDefs": [{
                            "targets": [0, 1, 2, 3, 8, 10, 11, 12, 13, 14, 15, 16, 17],
                            "visible": false
                        },
                        {
                            "targets": [8, 9],
                            "className": 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number('.', ',', 2, '').display(data);

                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        }
                    ],
                    "order": [
                        [17, "des"]
                    ]
                });

                // SB Acknowledge
                var tableAcknowledge = $('#view_project_list4').DataTable({
                    dom: 'Blrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa fa-circle-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=order_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB'><i class='fa fa-plus'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=project_list";
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit SB'><i class='fa fa-pen'></i></span>",
                            action: function() {
                                var rownumber = tableAcknowledge.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableAcknowledge.cell(rownumber, 1).data();
                                var so_number = tableAcknowledge.cell(rownumber, 2).data();
                                alert(project_code);
                                var order_number = tableAcknowledge.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=edit&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='View SB'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = tableAcknowledge.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableAcknowledge.cell(rownumber, 1).data();
                                var so_number = tableAcknowledge.cell(rownumber, 2).data();
                                var order_number = tableAcknowledge.cell(rownumber, 3).data();
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=view&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Approved";
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span  class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Copy SB'><i class='fa fa-copy'></i></span>",
                            action: function() {
                                var rownumber = tableAcknowledge.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableAcknowledge.cell(rownumber, 1).data();
                                var so_number = tableAcknowledge.cell(rownumber, 2).data();
                                var order_number = tableAcknowledge.cell(rownumber, 3).data();
                                document.getElementById("project_code").value = project_code;
                                document.getElementById("so_number").value = so_number;
                                document.getElementById("order_number").value = order_number;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    window.location.href = "index.php?mod=service_budget&act=copy&project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&submit=Draft";
                                }
                            },
                            enabled: false
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export PDF'><i class='fa fa-file-pdf'></i></span>",
                            action: function() {
                                var rownumber = tableAcknowledge.rows({
                                    selected: true
                                }).indexes();
                                var project_code = tableAcknowledge.cell(rownumber, 1).data();
                                var so_number = tableAcknowledge.cell(rownumber, 2).data();
                                var order_number = tableAcknowledge.cell(rownumber, 3).data();
                                var user_name = document.getElementById("user_name").value;
                                if (project_code == null) {
                                    alert("Please select the data.");
                                } else {
                                    // window.location.href = "components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V2.php?project_code="+project_code+"&so_number="+so_number+"&order_number="+order_number+"&user="+user_name;
                                    window.open("components/vendor/TCPDF-main/examples/rpt_service_budget_pdf_V3.php?project_code=" + project_code + "&so_number=" + so_number + "&order_number=" + order_number + "&user=" + user_name, "_blank");
                                }
                            },
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='All My Service Budget'><i class='fa fa-database'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&act=all";
                            },
                            enabled: false
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filter" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        },
                        {
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add SB by Order Number'><i class='fa-solid fa-cart-shopping'></i></span>",
                            action: function() {
                                window.location.href = "index.php?mod=service_budget&sub=list_boq";
                            },
                            enabled: true
                        }, 
                        {
                            text: "<i class='fa fa-book' data-bs-toggle='popover' data-bs-trigger='focus' title='Report '></i>",
                            action: function() {
                                window.location.href = "index.php?mod=report_acknowledge_service_budget";
                            },
                            enabled: true
                        },
                    ],
                    "columnDefs": [{
                            "targets": [0, 1, 2, 3, 8, 10, 11, 12, 13, 14, 15, 16, 17],
                            "visible": false
                        },
                        {
                            "targets": [8, 9],
                            "className": 'dt-body-right',
                            render: function(data, type) {
                                var number = $.fn.dataTable.render.number('.', ',', 2, '').display(data);

                                if (type === 'display') {
                                    let color = 'black';
                                    if (data > 200000000) {
                                        color = 'green';
                                    }
                                    return '<span style="color:' + color + '">' + number + '</span>';
                                }
                                return number;
                            }
                        }
                    ],
                    "order": [
                        [17, "des"]
                    ]
                });

                var user_email = document.getElementById('user_email').value;
                if (user_email == "syamsul@mastersystem.co.id" || user_email == "iwan@mastersystem.co.id") {
                    tableUnApproved.button(8).enable();
                    tableSubmit.button(8).enable();
                    tableApprovedTrade.button(8).enable();
                    tableApproved.button(8).enable();
                    tableTempApproved.button(8).enable();
                    tableAcknowledge.button(8).enable();
                } else {
                    tableUnApproved.button(8).disable();
                    tableSubmit.button(8).disable();
                    tableApprovedTrade.button(8).disable();
                    tableApproved.button(8).disable();
                    tableTempApproved.button(8).disable();
                    tableAcknowledge.button(8).disable();
                }


            });
        </script>

        <?php
        // Function
        function view_data($tblname)
        {
            global $DTSB;
            $tblname = "view_project_list";
            $primarykey = "project_id";
            $order = "project_id DESC";
            $filter = "";
            $sambung = "";
            if (isset($_GET['project_code']) && $_GET['project_code'] != "") {
                $filter = "project_code LIKE '%" . trim($_GET['project_code'], " ") . "%'";
                $sambung = " OR ";
            }
            if (isset($_GET['so_number']) && $_GET['so_number'] != "") {
                $filter .= $sambung . " so_number LIKE '%" . trim($_GET['so_number'], " ") . "%'";
                $sambung = " OR ";
            }
            if (isset($_GET['order_number']) && $_GET['order_number'] != "") {
                $filter .= $sambung . " order_number LIKE '%" . trim($_GET['order_number'], " ") . "%'";
            }
        ?>

            <?php
            if (isset($_GET['err']) && $_GET['err'] == "datanotselected") {
                global $ALERT;
                $ALERT->datanotselected();
            }
            ?>
            <input type="hidden" id="user_email" value="<?php echo $_SESSION['Microservices_UserEmail']; ?>">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-secondary">Select Project</h6>
                        <?php spinner(); ?>
                        <div class="align-items-right">
                            <!-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button> -->
                        </div>
                    </div>

                    <div class="card-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active text-body" id="home-tab" data-bs-toggle="tab" data-bs-target="#SBList" type="button" role="tab" aria-controls="SBList" aria-selected="true" title='SB yang masih dalam bentuk draft'>SB Draft</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-body" id="submit-tab" data-bs-toggle="tab" data-bs-target="#SBSubmit" type="button" role="tab" aria-controls="SBSubmit" aria-selected="false" title='SB yang sudah disubmit ke manager'>SB Submit</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-body" id="approvalTrade-tab" data-bs-toggle="tab" data-bs-target="#SBApprovedTrade" type="button" role="tab" aria-controls="SBApprovedTrade" aria-selected="false" title='SB yang sudah diapproved oleh manager'>SB Approved Trade</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-body" id="approval-tab" data-bs-toggle="tab" data-bs-target="#SBApproved" type="button" role="tab" aria-controls="SBApproved" aria-selected="false" title='SB yang sudah diapproved oleh manager'>SB Approved Project</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-body" id="temporary-tab" data-bs-toggle="tab" data-bs-target="#SBTemp" type="button" role="tab" aria-controls="SBTemp" aria-selected="false" title='SB yang sudah final tapi so number atau order number atau po number masih kosong'>SB Temporary</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-body" id="final-tab" data-bs-toggle="tab" data-bs-target="#SBFinal" type="button" role="tab" aria-controls="SBFinal" aria-selected="false" title='SB yang sudah final dan siap diassign ke projek'>SB Acknowledge</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <!-- TAB Project Information -->
                            <?php
                            $mdlname = "HCM";
                            $DBHCM = get_conn($mdlname);
                            $tblname = "view_employees";
                            $condition = "leader_email='" . $_SESSION['Microservices_UserEmail'] . "'";
                            $leaders = $DBHCM->get_data($tblname, $condition);

                            $members = "create_by='" . $_SESSION['Microservices_UserEmail'] . "' OR sales_name LIKE '%" . $_SESSION['Microservices_UserName'] . "%' OR `ps_account` = '" . $_SESSION['Microservices_UserEmail'] . "'";
                            $organization = "";
                            if ($leaders[2] > 0) {
                                $organization = trim($leaders[0]['organization_name']);
                                // if($leaders[0]['organization_name']=="Presales Engineer" || $leaders[0]['organization_name']=="Sales" || $leaders[0]['organization_name']=="Telesales" || $leaders[0]['organization_name']=="Marketing" || $leaders[0]['organization_name']=="Presales Engineering" || $leaders[0]['organization_name']=="Solution Architect" || $leaders[0]['organization_name']=="Solution Engineer" || $leaders[0]['organization_name']=="Technology Solution Collaboration" || $leaders[0]['organization_name']=="Enterprise Presales Account" || $leaders[0]['organization_name']=="Contact Center & Custom Software" || $leaders[0]['organization_name']=="Enterprise Presales Account" || $leaders[0]['organization_name']=="Account Manager") { 
                                if ($organization == "Presales Engineer" || $organization == "Sales" || $organization == "Telesales" || $organization == "Marketing" || $organization == "Presales Engineering" || $organization == "Solution Architect" || $organization == "Solution Engineer" || $organization == "Technology Solution Collaboration" || $organization == "Enterprise Presales Account" || $organization == "Contact Center & Custom Software" || $organization == "Enterprise Presales Account" || $organization == "Account Manager") {
                                    do {
                                        $members .= " OR create_by='" . $leaders[0]['employee_email'] . "' OR sales_name LIKE '%" . $leaders[0]['employee_name'] . "%' OR `ps_account` = '" . $leaders[0]['employee_email'] . "'";

                                        $condition = "leader_email='" . $leaders[0]['employee_email'] . "'";
                                        $employees = $DBHCM->get_data($tblname, $condition);
                                        if ($employees[2] > 0) {
                                            do {
                                                $members .= " OR create_by='" . $employees[0]['employee_email'] . "' OR sales_name LIKE '%" . $employees[0]['employee_name'] . "%' OR `ps_account` = '" . $leaders[0]['employee_email'] . "'";
                                            } while ($employees[0] = $employees[1]->fetch_assoc());
                                        }
                                    } while ($leaders[0] = $leaders[1]->fetch_assoc());
                                }
                            }

                            $tblnamevalue = "mst_setup";
                            $conditionvalue = "setup_name='Value SB Sederhana'";
                            $order = "modified_date DESC";
                            $sbs = $DTSB->get_data($tblnamevalue, $conditionvalue);
                            $dsbs = $sbs[0];
                            $dsbsexp = explode(";", $dsbs['setup_value']);
                            $dsbidrexp = explode("=", $dsbsexp[0]);
                            $dsbid = $dsbidrexp[1];
                            $dsbusdexp = explode("=", $dsbsexp[1]);
                            $dsbusd = $dsbusdexp[1];

                            $tblname = "view_project_list";
                            $maxRows = 100;
                            ?>
                            <div class="tab-pane fade show active" id="SBList" role="tabpanel" aria-labelledby="SBList-tab">
                                <div class="card  mb-4">
                                    <?php
                                    if ($filter != "") {
                                        $condition = "(" . $filter . ") AND (status='draft' OR status='rejected' OR status='reopen')";
                                        $maxRows = 0;
                                    } elseif (isset($_GET['act']) && $_GET['act'] == 'all') {
                                        $condition = "status='draft' OR status='rejected' OR status='reopen'";
                                    } else {
                                        $condition = "(status='draft' OR status='rejected' OR status='reopen') AND (" . $members . ")";
                                    }
                                    view_table($DTSB, $tblname, $primarykey, $condition, $order);
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SBSubmit" role="tabpanel" aria-labelledby="SBSubmit-tab">
                                <div class="card  mb-4">
                                    <?php
                                    if ($filter != "") {
                                        $condition = "(" . $filter . ") AND status='submited'";
                                        $maxRows = 0;
                                    } elseif (isset($_GET['act']) && $_GET['act'] == 'all') {
                                        $condition = "status='submited'";
                                    } else {
                                        $condition = "status='submited' AND (" . $members . ")";
                                    }
                                    // if($_SESSION['Microservices_UserEmail']=="syamsul@mastersystem.co.id")
                                    // {
                                    //     $condition = "status='submited' AND (`create_by` LIKE 'vika%' OR `create_by` LIKE 'anna%')";
                                    // } 
                                    view_table($DTSB, $tblname, $primarykey, $condition, $order, 0, $maxRows, "1");
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SBApprovedTrade" role="tabpanel" aria-labelledby="SBApproved-tab">
                                <div class="card shadow mb-4">
                                    <?php
                                    if ($filter != "") {
                                        $condition = "(" . $filter . ") AND status='approved'";
                                        $maxRows = 0;
                                    } elseif ((isset($_GET['act']) && $_GET['act'] == 'all') || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
                                        $condition = "status='approved'";
                                    } else {
                                        $condition = "status='approved' AND (" . $members . ")";
                                    }
                                    $condition .= " AND `bundling`='- Trade<br/>'";
                                    view_table($DTSB, $tblname, $primarykey, $condition, $order, 0, $maxRows, "2");
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SBApproved" role="tabpanel" aria-labelledby="SBApproved-tab">
                                <div class="card shadow mb-4">
                                    <?php
                                    if ($filter != "") {
                                        $check_data = $DTSB->get_sqlV2("SELECT * FROM sa_trx_project_list WHERE ($filter) AND (status='approved' OR status='acknowledge')");
                                        $check_status_ackimp = $check_data[0]['pmo_ack_implementation'];
                                        $check_status_ackmt = $check_data[0]['pmo_ack_maintenance'];
                                        if (empty($check_status_ackmt) && empty($check_status_ackimp)) {
                                            $condition = "(" . $filter . ") AND status='approved'";
                                        } else if (!empty($check_status_ackmt) && empty($check_status_ackimp) && ($_SESSION['Microservices_UserEmail'] == "fortuna@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "sumarno@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "pitasari.amanda@mastersystem.co.id")) {
                                            $condition = "(" . $filter . ") AND (status='acknowledge' OR status='approved')";
                                        } else if (empty($check_status_ackmt) && !empty($check_status_ackimp) && ($_SESSION['Microservices_UserEmail'] == "aceng.zakariya@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "anggi.fachrizal@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "miko.widiarta@mastersystem.co.id")) {
                                            // echo $check_status_ackimp . " " . $check_status_ackmt . " " . $_SESSION['Microservices_UserEmail'];
                                            $condition = "(" . $filter . ") AND (status='acknowledge' OR status='approved')";
                                        } else {
                                            $condition = "(" . $filter . ") AND status='approved'";
                                        }
                                        // $condition = "(" . $filter . ") AND status='approved'";
                                        $maxRows = 0;
                                    } elseif ((isset($_GET['act']) && $_GET['act'] == 'all') || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
                                        $condition = "status='approved'";
                                    } else {
                                        $condition = "status='approved' AND (" . $members . ")";
                                    }
                                    $condition .= " AND `bundling`<>'- Trade<br/>'";
                                    view_table($DTSB, $tblname, $primarykey, $condition, $order, 0, $maxRows, "21");
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SBTemp" role="tabpanel" aria-labelledby="SBTemp-tab">
                                <div class="card shadow mb-4">
                                    <?php
                                    if ($filter != "") {
                                        $condition = "(" . $filter . ") AND status='acknowledge' AND (ISNULL(po_number) OR ISNULL(so_number) OR ISNULL(order_number))";
                                        $maxRows = 0;
                                    } elseif ((isset($_GET['act']) && $_GET['act'] == 'all') || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
                                        $condition = "status='acknowledge' AND (ISNULL(po_number) OR ISNULL(so_number) OR ISNULL(order_number))";
                                    } else {
                                        $condition = "status='acknowledge' AND (ISNULL(po_number) OR ISNULL(so_number) OR ISNULL(order_number)) AND (" . $members . ")";
                                    }
                                    view_table($DTSB, $tblname, $primarykey, $condition, $order, 0, $maxRows, "3");
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="SBFinal" role="tabpanel" aria-labelledby="SBFinal-tab">
                                <div class="card shadow mb-4">
                                    <?php
                                    if ($filter != "") {
                                        $check_data2 = $DTSB->get_sqlV2("SELECT * FROM sa_trx_project_list WHERE ($filter) AND status='acknowledge'");
                                        // $check_status_ackimp2 = $check_data2[0]['pmo_ack_implementation'];
                                        // $check_status_ackmt2 = $check_data2[0]['pmo_ack_maintenance'];
                                        // if (empty($check_status_ackmt2) && empty($check_status_ackimp2)) {
                                        //     $condition = "(" . $filter . ") AND status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number)";
                                        // } else if (empty($check_status_ackmt2) && !empty($check_status_ackimp2) && ($_SESSION['Microservices_UserEmail'] == "fortuna@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "sumarno@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "pitasari.amanda@mastersystem.co.id")) {
                                        //     $condition = "(" . $filter . ") AND status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number)";
                                        // } else if (!empty($check_status_ackmt2) && empty($check_status_ackimp2) && ($_SESSION['Microservices_UserEmail'] == "aceng.zakariya@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "anggi.fachrizal@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "miko.widiarta@mastersystem.co.id")) {
                                        //     $condition = "(" . $filter . ") AND status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number)";
                                        // } else {
                                        //$condition = "(" . $filter . ") AND status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number)";
                                        // }
                                        $condition = "(" . $filter . ") AND status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number)";
                                        $maxRows = 0;
                                    } elseif ((isset($_GET['act']) && $_GET['act'] == 'all') || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
                                        $condition = "status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number)";
                                    } else {
                                        $condition = "status='acknowledge' AND NOT ISNULL(po_number) AND NOT ISNULL(so_number) AND NOT ISNULL(order_number) AND (" . $members . ")";
                                    }
                                    view_table($DTSB, $tblname, $primarykey, $condition, $order, 0, $maxRows, "4");
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // module update
                    $title = "Service Budget Listing"; // Title of module or function
                    $author = 'Syamsul Arham'; // Author or created by
                    $type = "main-module"; // main-module is mod_xxxx.php, sub-module is form, function is function not form
                    $revisionType = 'control'; // major, minor, control
                    $dashboardEnable = false;
                    $moduleDesc = "This module is used to view a list of Service Budgets that have been created."; // Decsribe about name or function of module
                    $revision_msg = "Update user Sylvia team for Service Budget"; // Describe the coding chnage made, if any
                    $showFooter = true; // Select true if you want ti display the version in the main-module or sub-module, otherwise select false 

                    global $ClassVersion;
                    $ClassVersion->show_footer(__FILE__, $title, $moduleDesc, $type, $revisionType, $dashboardEnable, $author, $revision_msg, $showFooter);
                    ?>
                </div>
            </div>
            <?php
            if ($filter != "") {
                $tblname = "trx_project_list";
                $check = $DTSB->get_data($tblname, $filter);
                if ($check[2] > 0) {
                    $draft = 0;
                    $submit = 0;
                    $approved = 0;
                    $acknowledge = 0;
                    $mdlname = "HCM";
                    $DBHCM = get_conn($mdlname);
                    $names = $DBHCM->get_leader_v2($check[0]['create_by']);

                    do {
                        if (empty($check[0]['pmo_ack_implementation']) && empty($check[0]['pmo_ack_maintenance'])) {
                            if ($check[0]['status'] == "draft" || $check[0]['status'] == "rejected" || $check[0]['status'] == "reopen") {
                                $draft++;
                                echo '<script>document.getElementById("home-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "submited") {
                                $submit++;
                                echo '<script>document.getElementById("submit-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "approved" && ($check[0]['bundling'] == '0;0;0;' || $check[0]['bundling'] == '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approvalTrade-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "approved" && ($check[0]['bundling'] != '0;0;0;' && $check[0]['bundling'] != '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "acknowledge" && (is_null($check[0]['po_number']) || is_null($check[0]['so_number']))) {
                                $acknowledge++;
                                echo '<script>document.getElementById("temporary-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "acknowledge") {
                                echo '<script>document.getElementById("final-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                        } else if ($_SESSION['Microservices_UserEmail'] == "aceng.zakariya@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "anggi.fachrizal@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "miko.widiarta@mastersystem.co.id") {
                            if ($check[0]['status'] == "draft" || $check[0]['status'] == "rejected" || $check[0]['status'] == "reopen") {
                                $draft++;
                                echo '<script>document.getElementById("home-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "submited") {
                                $submit++;
                                echo '<script>document.getElementById("submit-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "acknowledge" && ($check[0]['bundling'] == '0;0;0;' || $check[0]['bundling'] == '0;0;0;0;') && empty($check[0]['pmo_ack_maintenance'])) {
                                $approved++;
                                echo '<script>document.getElementById("approvalTrade-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "acknowledge" && ($check[0]['bundling'] != '0;0;0;' || $check[0]['bundling'] != '0;0;0;0;') && empty($check[0]['pmo_ack_maintenance'])) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "approved" && ($check[0]['bundling'] != '0;0;0;' || $check[0]['bundling'] != '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "acknowledge" && (is_null($check[0]['po_number']) || is_null($check[0]['so_number']))) {
                                $acknowledge++;
                                echo '<script>document.getElementById("temporary-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "acknowledge" && !empty($check[0]['pmo_ack_maintenance'])) {
                                echo '<script>document.getElementById("final-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                        } else if ($_SESSION['Microservices_UserEmail'] == "fortuna@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "sumarno@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "pitasari.amanda@mastersystem.co.id") {
                            if ($check[0]['status'] == "draft" || $check[0]['status'] == "rejected" || $check[0]['status'] == "reopen") {
                                $draft++;
                                echo '<script>document.getElementById("home-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "submited") {
                                $submit++;
                                echo '<script>document.getElementById("submit-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "approved" && ($check[0]['bundling'] != '0;0;0;' || $check[0]['bundling'] != '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "acknowledge" && ($check[0]['bundling'] == '0;0;0;' || $check[0]['bundling'] == '0;0;0;0;') && empty($check[0]['pmo_ack_implementation'])) {
                                $approved++;
                                echo '<script>document.getElementById("approvalTrade-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "acknowledge" && ($check[0]['bundling'] != '0;0;0;' || $check[0]['bundling'] != '0;0;0;0;') && empty($check[0]['pmo_ack_implementation'])) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "acknowledge" && (is_null($check[0]['po_number']) || is_null($check[0]['so_number'])) && (!empty($check[0]['pmo_ack_implementation']))) {
                                $acknowledge++;
                                echo '<script>document.getElementById("temporary-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "acknowledge" && !empty($check[0]['pmo_ack_implementation'])) {
                                echo '<script>document.getElementById("final-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                        } else {
                            if ($check[0]['status'] == "draft" || $check[0]['status'] == "rejected" || $check[0]['status'] == "reopen") {
                                $draft++;
                                echo '<script>document.getElementById("home-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "submited") {
                                $submit++;
                                echo '<script>document.getElementById("submit-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "approved" && ($check[0]['bundling'] != '0;0;0;' || $check[0]['bundling'] != '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "approved" && ($check[0]['bundling'] == '0;0;0;' || $check[0]['bundling'] == '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approvalTrade-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "approved" && ($check[0]['bundling'] != '0;0;0;' || $check[0]['bundling'] != '0;0;0;0;')) {
                                $approved++;
                                echo '<script>document.getElementById("approval-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                            if ($check[0]['status'] == "acknowledge" && (is_null($check[0]['po_number']) || is_null($check[0]['so_number']))) {
                                $acknowledge++;
                                echo '<script>document.getElementById("temporary-tab").style.backgroundColor ="#FFC7CE";</script>';
                            } else
                        if ($check[0]['status'] == "acknowledge") {
                                echo '<script>document.getElementById("final-tab").style.backgroundColor ="#FFC7CE";</script>';
                            }
                        }
                    } while ($check[0] = $check[1]->fetch_assoc());
            ?>
                    <script>
                        alert("<?php echo 'Berikut status Service Budget yang dimaksudkan :\r\nDraft : ' . $draft . '\r\nSubmit : ' . $submit . '\r\nApproved : ' . $approved . '\r\nTemporary/Acknowledge : ' . $acknowledge . '\r\nCreated by : ' . $names[1]; ?>");
                    </script>
                <?php
                } else {
                ?>
                    <script>
                        alert("Service Budget belum ada.");
                    </script>
            <?php
                }
            }
            ?>

            <!-- Modal -->
            <div class="modal fade" id="filter" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="saveLabel"><b>Search Service Budget</b></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="get" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="project_code" class="col-form-label">Project Code:</label>
                                    <input type="text" class="form-control" id="project_code" name="project_code">
                                </div>
                                <div class="mb-3">
                                    <label for="order_number" class="col-form-label">Order Number:</label>
                                    <input type="text" class="form-control" id="order_number" name="order_number">
                                </div>
                                <div class="mb-3">
                                    <label for="order_number" class="col-form-label">SO Number:</label>
                                    <input type="text" class="form-control" id="so_number" name="so_number">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="mod" value="service_budget">
                                <!-- <input type="hidden" name="act" value="copy"> -->
                                <input type="submit" class="btn btn-primary" name="search_order" id="search_order" value="Search">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

<?php
        }

        function form_data($tblname, $permission = "")
        {
            include("components/modules/service_budget/form_service_budget.php");
        }

        // End Function


        $tblname = 'cfg_web';
        $condition = 'config_key="MODULE_SERVICE_BUDGET"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if ($setupDB[2] > 0) {
            // $params = get_params($dsetupDB['params']);
            // $hostname = $params['database']['hostname'];
            // $username = $params['database']['username'];
            // $userpassword = $params['database']['userpassword'];
            // $database = $params['database']['database_name'];

            // $DTSB = new Databases($hostname, $username, $userpassword, $database);
            include("components/classes/func_service_budget.php");
            $mdlname = "SERVICE_BUDGET";
            $DTSB = get_conn($mdlname);
            $tblname = 'view_project_list';

            include("components/modules/service_budget/func_service_budget.php");
            include("components/modules/service_budget/func_service_budget_copy.php");

            // Body
            if (!isset($_GET['act']) || (isset($_GET['act']) && $_GET['act'] == 'all')) {
                view_data($tblname);
            } elseif ($_GET['act'] == 'add') {
                form_data($tblname);
            } elseif ($_GET['act'] == 'new') {
                // new_projects($tblname);
            } elseif ($_GET['act'] == 'edit') {
                $permission = '';
                form_data($tblname, $permission);
            } elseif ($_GET['act'] == 'redit') {
                $permission = '';
                include("components/modules/service_budget/form_sb_redit.php");
            } elseif ($_GET['act'] == 'del') {
                echo 'Delete Data';
            } elseif ($_GET['act'] == 'save') {
                form_data($tblname);
            } elseif ($_GET['act'] == 'view') {
                $permission = "readonly";
                form_data($tblname, $permission);
            } elseif ($_GET['act'] == 'export') {
                include('components/modules/service_budget/rpt_excel.php');
            } elseif ($_GET['act'] == 'lookup_project') {
                include('components/modules/service_budget/form_lookup_project.php');
            } elseif ($_GET['act'] == 'order') {
                include('components/modules/service_budget/form_service_budget.php');
            } elseif ($_GET['act'] == 'copy') {
                include('components/modules/service_budget/form_service_budget_copy.php');
            }
            // End Body

        } else {
            echo "Aplikasi belum disetup";
        }

        // Body
    } else {
        $ALERT->notpermission();
    }
    // End Body

} elseif (isset($_GET['sub']) && $_GET['sub'] == 'poc_service_budget') {
    include('components/modules/service_budget/poc_service_budget.php');
} else {
    include("components/modules/service_budget/mod_file_boq.php");
}

?>