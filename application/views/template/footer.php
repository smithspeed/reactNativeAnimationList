<!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © <?=date('Y')?> AutoBot
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?=base_url()?>assets/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?=base_url()?>assets/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url()?>assets/js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="<?=base_url()?>assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?=base_url()?>assets/js/sidebarmenu.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!-- chartist chart -->
    <!-- <script src="<?=base_url()?>assets/plugins/chartist-js/dist/chartist.min.js"></script> -->
    <!-- <script src="<?=base_url()?>assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script> -->
    <!--c3 JavaScript -->
    <script src="<?=base_url()?>assets/plugins/d3/d3.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/c3-master/c3.min.js"></script>
    <!--Custom JavaScript -->
    <!-- <script src="<?=base_url()?>assets/js/pages/dashboards/dashboard1.js"></script> -->

    <?php if(isset($template)){ foreach($template as $key => $val) {
        $this->load->view('template/'.$val,['section'=>'footer']);
    }} ?>

    <script src="<?=base_url()?>assets/js/custom.js"></script>
</body>

</html>