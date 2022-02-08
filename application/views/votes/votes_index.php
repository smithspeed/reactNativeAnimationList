<?php $this->load->view('template/header.php'); ?>
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="page-title mb-0 p-0">Votes</h3>
            
        </div>
        <div class="col-md-6 col-4 align-self-center">
            
        </div>
    </div>
</div>       
<div class="container-fluid">
    <div class="d-flex flex-row justify-content-end mb-3">
        <button class="btn btn-primary addVotes">Add Votes</button>
        <button class="btn btn-info refreshVotes ml-2">Refresh</button>
    </div>
    <table id="botTable" class="display table table-striped table-bordered table-sm" style="width:100%">
        <thead>
            <tr>
                <th>Coin Id</th>
                <th>Coin Name</th>
                <th>Total Votes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php $this->load->view('template/footer.php');?>

<script type="text/javascript">

    var AutoBot = {},
    SYSTEM,botDataTable;

    $(document).ready(function() {

        ACTIONS = {

            addVotes:function(){

                $('.addVotes').click(function(){

                    AlERT_VIEW.votesAdd();
                });
            },
            refreshVotes:function(){

                $('.refreshVotes').click(function(){

                    botDataTable.ajax.reload();
                });
            }
        }

        AlERT_VIEW = {

            votesAdd:function(){

                var did = "ADDVOTES";
                var xpop = 'addVotesDone';
                var tabName = 'votes'; 
                $.alert({
                    columnClass: 'col-md-6 col-md-offset-3',
                    draggable: true,
                    keyboardEnabled: false,
                    closeIcon: false,
                    backgroundDismiss: false,
                    title: '',
                    smoothContent: false,
                    content: '',
                    onOpenBefore: function() {
                        
                        var self = this;
                        var rhtml = '<div class="panel" style="margin-bottom: 0px;" id="BODY-' + xpop + '">';
                            rhtml += '  <header class="panel-heading"  style="padding-top:0px;text-transform: none;padding-bottom: 0;">';
                            rhtml += '    <ul class="nav nav-tabs x-pop">';
                            rhtml += '        <li class="active" id="'+tabName+'Frm">';
                            rhtml += '            <a href="#TEXT-'+xpop+'" data-toggle="tab">Add Votes</a>';
                            rhtml += '        </li>';
                            rhtml += '        <li class="hidden" id="'+tabName+'Cnf">';
                            rhtml += '            <a href="#TEXT2-'+xpop+'" data-toggle="tab" >Details</a>';
                            rhtml += '        </li>';
                            rhtml += '        <li class="hidden" id="'+tabName+'Cnf1">';
                            rhtml += '            <a href="#TEXT3-'+xpop+'" data-toggle="tab" >Details</a>';
                            rhtml += '        </li>';
                            rhtml += '    </ul>';
                            rhtml += '  </header>';
                            rhtml += '  <div id="LOADER-'+xpop+'" style="overflow: hidden; height: 1px; opacity: 1;">';
                            rhtml += '    <div class="indeterminate"></div>';
                            rhtml += '  </div>';
                            rhtml += '  <div class="panel-body" style="padding-bottom:0px">';
                            rhtml += '    <div class="tab-content">';
                            rhtml += '       <div class="tab-pane active" id="TEXT-'+xpop+'">';
                            rhtml += '          Please wait while getting the details';
                            rhtml += '       </div>';
                            rhtml += '       <div class="tab-pane" id="TEXT2-'+xpop+'">';
                            rhtml += '          Please wait while getting the details.';
                            rhtml += '       </div>';
                            rhtml += '       <div class="tab-pane" id="TEXT3-'+xpop+'">';
                            rhtml += '          Please wait while getting the details.';
                            rhtml += '       </div>';
                            rhtml += '   </div>';
                            rhtml += '  </div>';
                            rhtml += '</div>';
                        self.setContent(rhtml);

                        
                        this.buttons.cancel.disable();
                        this.buttons.cancel.hide();
                        this.buttons.confirmButton.disable();
                        this.buttons.confirmButton.hide();

                    },
                    onOpen: function() {
                        
                        this.buttons.cancel.enable();
                        this.buttons.cancel.show();
                        this.buttons.confirmButton.enable();
                        this.buttons.confirmButton.show();
                        
                        var self = this;

                        $("#LOADER-"+xpop).css('opacity',1);

                        GVHTML = `
                            <form class="form-horizontal mx-2 mt-4">
                                <div class="form-group">
                                    <label class="col-md-12 mb-0">Coin Id</label>
                                    <div class="col-md-12">
                                        <input type="text" id="coinId" class="form-control ps-0 form-control-line getData">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12 mb-0">Number of Votes</label>
                                    <div class="col-md-12">
                                        <input type="text" id="numberVotes" class="form-control ps-0 form-control-line getData">
                                    </div>
                                </div>
                            </form>
                        `;
                        

                        $("#TEXT-"+xpop).html(GVHTML);

                        $("#LOADER-"+xpop).css('opacity',0);
                    },
                    buttons:{
                        confirmButton:{
                            text: 'PROCEED',
                            btnClass: 'btn btn-primary',
                            action:function(){

                                var self = this;

                                $("#LOADER-"+xpop).css('opacity',1);

                                let totalData = {};

                                totalData['coinId'] = $('#coinId').val();

                                totalData['numberVotes'] = $('#numberVotes').val();

                                $.post("<?=site_url('votes/storeVotes')?>",totalData,function(data){

                                    $("#LOADER-"+xpop).css('opacity',0);
                                    var obj = $.parseJSON(data);

                                    if(obj.statuscode == 'TXN'){

                                        self.buttons.confirmButton.disable();
                                        self.buttons.confirmButton.hide();

                                        var topupSuccessContent = `<p><span class="label label-success">Success</span> ${obj.status}</p>`;

                                        $("#TEXT-"+xpop).html(topupSuccessContent);

                                        botDataTable.ajax.reload();
                                    }
                                    else
                                    {
                                        var topupSuccessContent = `<p><span class="label label-danger">Error</span> ${obj.status}</p>`;

                                        $("#TEXT-"+xpop).html(topupSuccessContent);
                                    }
                                });

                                return false;
                            }
                        },
                        cancel: {
                            text: 'CLOSE',
                            key:['esc'],
                            action: function() {
                                // close
                            }
                        },
                    }

                }); 
            },
            votesEdit:function(passCoinId){

                var did = "EDITVOTES";
                var xpop = 'editVoteDone';
                var tabName = 'votes'; 
                $.alert({
                    columnClass: 'col-md-6 col-md-offset-3',
                    draggable: true,
                    keyboardEnabled: false,
                    closeIcon: false,
                    backgroundDismiss: false,
                    title: '',
                    smoothContent: false,
                    content: '',
                    onOpenBefore: function() {
                        
                        var self = this;
                        var rhtml = '<div class="panel" style="margin-bottom: 0px;" id="BODY-' + xpop + '">';
                            rhtml += '  <header class="panel-heading"  style="padding-top:0px;text-transform: none;padding-bottom: 0;">';
                            rhtml += '    <ul class="nav nav-tabs x-pop">';
                            rhtml += '        <li class="active" id="'+tabName+'Frm">';
                            rhtml += '            <a href="#TEXT-'+xpop+'" data-toggle="tab">Update Votes</a>';
                            rhtml += '        </li>';
                            rhtml += '        <li class="hidden" id="'+tabName+'Cnf">';
                            rhtml += '            <a href="#TEXT2-'+xpop+'" data-toggle="tab" >Details</a>';
                            rhtml += '        </li>';
                            rhtml += '        <li class="hidden" id="'+tabName+'Cnf1">';
                            rhtml += '            <a href="#TEXT3-'+xpop+'" data-toggle="tab" >Details</a>';
                            rhtml += '        </li>';
                            rhtml += '    </ul>';
                            rhtml += '  </header>';
                            rhtml += '  <div id="LOADER-'+xpop+'" style="overflow: hidden; height: 1px; opacity: 1;">';
                            rhtml += '    <div class="indeterminate"></div>';
                            rhtml += '  </div>';
                            rhtml += '  <div class="panel-body" style="padding-bottom:0px">';
                            rhtml += '    <div class="tab-content">';
                            rhtml += '       <div class="tab-pane active" id="TEXT-'+xpop+'">';
                            rhtml += '          Please wait while getting the details';
                            rhtml += '       </div>';
                            rhtml += '       <div class="tab-pane" id="TEXT2-'+xpop+'">';
                            rhtml += '          Please wait while getting the details.';
                            rhtml += '       </div>';
                            rhtml += '       <div class="tab-pane" id="TEXT3-'+xpop+'">';
                            rhtml += '          Please wait while getting the details.';
                            rhtml += '       </div>';
                            rhtml += '   </div>';
                            rhtml += '  </div>';
                            rhtml += '</div>';
                        self.setContent(rhtml);

                        
                        this.buttons.cancel.disable();
                        this.buttons.cancel.hide();
                        this.buttons.confirmButton.disable();
                        this.buttons.confirmButton.hide();

                    },
                    onOpen: function() {
                        
                        this.buttons.cancel.enable();
                        this.buttons.cancel.show();
                        this.buttons.confirmButton.enable();
                        this.buttons.confirmButton.show();
                        
                        var self = this;

                        $("#LOADER-"+xpop).css('opacity',1);

                        GVHTML = `
                            <form class="form-horizontal mx-2 mt-4">
                                <div class="form-group">
                                    <label class="col-md-12 mb-0">Remove Number of Votes</label>
                                    <div class="col-md-12">
                                        <input type="text" id="noVotes" class="form-control ps-0 form-control-line getData">
                                    </div>
                                </div>
                            </form>
                        `;

                        $("#LOADER-"+xpop).css('opacity',0);
                        
                        $("#TEXT-"+xpop).html(GVHTML);

                    },
                    buttons:{
                        confirmButton:{
                            text: 'PROCEED',
                            btnClass: 'btn btn-primary',
                            action:function(){

                                var self = this;

                                $("#LOADER-"+xpop).css('opacity',1);

                                let totalData = {};

                                totalData['numberVotes'] = $('#noVotes').val();

                                totalData['coinId'] = passCoinId;

                                $.post("<?=site_url('votes/removeVotes')?>",totalData,function(data){

                                    $("#LOADER-"+xpop).css('opacity',0);
                                    var obj = $.parseJSON(data);

                                    if(obj.statuscode == 'TXN'){

                                        self.buttons.confirmButton.disable();
                                        self.buttons.confirmButton.hide();

                                        var topupSuccessContent = `<p><span class="label label-success">Success</span> ${obj.status}</p>`;

                                        $("#TEXT-"+xpop).html(topupSuccessContent);

                                        botDataTable.ajax.reload();
                                    }
                                    else
                                    {
                                        var topupSuccessContent = `<p><span class="label label-danger">Error</span> ${obj.status}</p>`;

                                        $("#TEXT-"+xpop).html(topupSuccessContent);
                                    }
                                });

                                return false;
                            }
                        },
                        cancel: {
                            text: 'CLOSE',
                            key:['esc'],
                            action: function() {
                                // close
                            }
                        },
                    }

                }); 
            },
            votesDelete:function(passCoinId){

                var did = "DELETEVOTES";
                var xpop = 'deleteVoteDone';
                var tabName = 'vote'; 
                $.alert({
                    columnClass: 'col-md-6 col-md-offset-3',
                    draggable: true,
                    keyboardEnabled: false,
                    closeIcon: false,
                    backgroundDismiss: false,
                    title: '',
                    smoothContent: false,
                    content: '',
                    onOpenBefore: function() {
                        
                        var self = this;
                        var rhtml = '<div class="panel" style="margin-bottom: 0px;" id="BODY-' + xpop + '">';
                            rhtml += '  <header class="panel-heading"  style="padding-top:0px;text-transform: none;padding-bottom: 0;">';
                            rhtml += '    <ul class="nav nav-tabs x-pop">';
                            rhtml += '        <li class="active" id="'+tabName+'Frm">';
                            rhtml += '            <a href="#TEXT-'+xpop+'" data-toggle="tab">Clear Votes</a>';
                            rhtml += '        </li>';
                            rhtml += '        <li class="hidden" id="'+tabName+'Cnf">';
                            rhtml += '            <a href="#TEXT2-'+xpop+'" data-toggle="tab" >Details</a>';
                            rhtml += '        </li>';
                            rhtml += '        <li class="hidden" id="'+tabName+'Cnf1">';
                            rhtml += '            <a href="#TEXT3-'+xpop+'" data-toggle="tab" >Details</a>';
                            rhtml += '        </li>';
                            rhtml += '    </ul>';
                            rhtml += '  </header>';
                            rhtml += '  <div id="LOADER-'+xpop+'" style="overflow: hidden; height: 1px; opacity: 1;">';
                            rhtml += '    <div class="indeterminate"></div>';
                            rhtml += '  </div>';
                            rhtml += '  <div class="panel-body" style="padding-bottom:0px">';
                            rhtml += '    <div class="tab-content">';
                            rhtml += '       <div class="tab-pane active" id="TEXT-'+xpop+'">';
                            rhtml += '          Please wait while getting the details';
                            rhtml += '       </div>';
                            rhtml += '       <div class="tab-pane" id="TEXT2-'+xpop+'">';
                            rhtml += '          Please wait while getting the details.';
                            rhtml += '       </div>';
                            rhtml += '       <div class="tab-pane" id="TEXT3-'+xpop+'">';
                            rhtml += '          Please wait while getting the details.';
                            rhtml += '       </div>';
                            rhtml += '   </div>';
                            rhtml += '  </div>';
                            rhtml += '</div>';
                        self.setContent(rhtml);

                        
                        this.buttons.cancel.disable();
                        this.buttons.cancel.hide();
                        this.buttons.confirmButton.disable();
                        this.buttons.confirmButton.hide();

                    },
                    onOpen: function() {
                        
                        this.buttons.cancel.enable();
                        this.buttons.cancel.show();
                        this.buttons.confirmButton.enable();
                        this.buttons.confirmButton.show();
                        
                        var self = this;

                        $("#LOADER-"+xpop).css('opacity',1);

                        GVHTML = `
                            <form class="form-horizontal mx-2 mt-4">
                                <span class="text-danger">Are you Sure want to Clear All Votes?</span>
                                
                            </form>
                        `;
                        

                        $("#TEXT-"+xpop).html(GVHTML);

                        $("#LOADER-"+xpop).css('opacity',0);

                    },
                    buttons:{
                        confirmButton:{
                            text: 'PROCEED',
                            btnClass: 'btn btn-primary',
                            action:function(){

                                var self = this;

                                $("#LOADER-"+xpop).css('opacity',1);

                                let totalData = {};

                                totalData['coinId'] = passCoinId;

                                $.post("<?=site_url('votes/clearVotes')?>",totalData,function(data){

                                    $("#LOADER-"+xpop).css('opacity',0);

                                    var obj = $.parseJSON(data);

                                    if(obj.statuscode == 'TXN'){

                                        self.buttons.confirmButton.disable();
                                        self.buttons.confirmButton.hide();

                                        var topupSuccessContent = `<p><span class="label label-success">Success</span> ${obj.status}</p>`;

                                        $("#TEXT-"+xpop).html(topupSuccessContent);

                                        botDataTable.ajax.reload();
                                    }
                                    else
                                    {
                                        var topupSuccessContent = `<p><span class="label label-danger">Error</span> ${obj.status}</p>`;

                                        $("#TEXT-"+xpop).html(topupSuccessContent);
                                    }
                                });

                                return false;
                            }
                        },
                        cancel: {
                            text: 'CLOSE',
                            key:['esc'],
                            action: function() {
                                // close
                            }
                        },
                    }

                }); 
            },
        }

        SYSTEM = {

            DEFAULT:function(){ 

                SYSTEM.DT_TABLE();

                ACTIONS.addVotes();

                ACTIONS.refreshVotes();

            },
            DT_DATA:function(){

                return {
                    destroy : true,
                    order: [],
                    ajax : {
                        url : "<?=base_url()?>votes/getList",
                        type : 'POST'
                    },
                    columns: [
                        { data: "coinId"},
                        { data: "coinName",},
                        { data: "totalVotes"},
                        { 
                            data: "coinId",
                            render: function ( data, type, row, meta ) {
                                return `
                                    <button data-id="${data}" class="btn btn-info btn-sm editVotes"> <i class="mdi mdi-table-edit" aria-hidden="true"></i> Remove Votes </button>
                                    <button data-id="${data}" class="btn btn-danger btn-sm deleteVotes"> <i class="mdi mdi-delete" aria-hidden="true"></i> Clear All Votes</button>
                                `;
                            }
                        },
                    ]
                }
            },
            DT_TABLE:function(){

                botDataTable = $('#botTable').DataTable(SYSTEM.DT_DATA());


                $(document.body).on('click','.editVotes', function(event){
                    var _this = $(this);
                    let rowData = botDataTable.row(_this.parent().parent()).data();
                    
                    AlERT_VIEW.votesEdit(rowData.coinId);
                });

                $(document.body).on('click','.deleteVotes', function(event){
                    var _this = $(this);
                    let rowData = botDataTable.row(_this.parent().parent()).data();
                    
                    AlERT_VIEW.votesDelete(rowData.coinId);
                });
            }
        }
        

        AutoBot.SYSTEM = SYSTEM
        AutoBot.ACTIONS = ACTIONS
        AutoBot.AlERT_VIEW = AlERT_VIEW

        AutoBot.INIT = (function(){

            SYSTEM.DEFAULT();
        })

        AutoBot.INIT()
    });

</script>
            