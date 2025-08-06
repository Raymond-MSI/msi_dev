<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <?php
            $DB = get_conn("MICROSERVICES");
            $tblname = 'cfg_web';
            $condition = 'config_key="TITLE_OF_WEBSITE"';
            $titles=$DB->get_data($tblname,$condition);
            $dtitle=$titles[0];
            $title=$dtitle['config_value'];
            ?>
            <span>Copyright &copy; <?php echo $title; ?> 2021</span>
        </div>
    </div>
</footer>
