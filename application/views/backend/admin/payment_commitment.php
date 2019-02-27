<?php $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description; ?>
<div class="row bg-title">
    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
        <h4 class="page-title"><?php echo get_phrase('payment-commitment'); ?></h4>
    </div>
    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>index.php?admin/admin_dashboard"><?php echo get_phrase('Dashboard'); ?></a></li>
            <li><a class="active"><?php echo get_phrase('payment-commitment'); ?></a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <ul class="nav nav-tabs bordered">
                <li class="active">
                    <a href="#unpaid" data-toggle="tab">
                        <span class="hidden-xs"><?php echo get_phrase('payment-commitment'); ?></span>
                    </a>
                </li>
<!--                <li>
                    <a href="#paid" data-toggle="tab">
                        <span class="hidden-xs"><?php echo get_phrase('payment_history'); ?></span>
                    </a>
                </li>-->
            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="unpaid">	
                    <div class="table-responsive">	
                        <table  id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;"><?php echo get_phrase('Pay'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Student'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('concept'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('anio'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Instalment'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('expiration-date'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Pension'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('default-rate'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Total'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Status'); ?></th>
                                    <th style="text-align: center;width: 20%"><?php echo get_phrase('Parent'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                #$this->db->where('year', $running_year);
                                #$this->db->order_by('creation_timestamp', 'desc');
                                #$invoices = $this->db->get('invoice')->result_array();
                                
                                #valido Usuario y contraseña;
                                $this->db->select('r_compromisos_pago.id_compromiso_pago,
                                                    r_compromisos_pago.student_id,
                                                    student.name as name,
                                                    student.student_code,
                                                    parent.name name_parent,
                                                    r_compromisos_pago.id_pago_concepto,
                                                    p_pago_conceptos.de_pago_concepto,
                                                    r_compromisos_pago.nu_anio,
                                                    r_compromisos_pago.nu_cuota,
                                                    r_compromisos_pago.fe_vencimiento,
                                                    r_compromisos_pago.mo_cuota_pagar,
                                                    r_compromisos_pago.mo_neto,
                                                    r_compromisos_pago.fe_caja,
                                                    r_compromisos_pago.mo_deuda_pago,
                                                    r_compromisos_pago.id_caja');
                                $this->db->from('r_compromisos_pago');
                                $this->db->join('student','r_compromisos_pago.student_id = student.student_id');                                
                                $this->db->join('parent','parent.parent_id = student.parent_id');
                                $this->db->join('p_pago_conceptos','p_pago_conceptos.id_pago_concepto = r_compromisos_pago.id_pago_concepto');
                                $this->db->where('r_compromisos_pago.in_esta',1);
                                $this->db->where('r_compromisos_pago.id_centro_educativo',1);
                                $invoices = $this->db->get()->result_array();
                                foreach ($invoices as $row){
                                    $status = $row['id_caja']? "Cancelado":"Pendiente";
                                    $dias = $this->crud_model->expiration_day($row['fe_vencimiento']);
                                    $pension = $row['mo_cuota_pagar'];
                                    $mora = $dias * 0.1;
                                    $total_pagar = $pension + $mora;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $count++; ?></td>
                                        <td style="text-align: center;" class="text-nowrap">
                                            <?php if(!$row['id_caja']){ ?>
                                            <a href="#" data-toggle="tooltip" onclick="showAjaxModal('<?php echo base_url(); ?>index.php?modal/popup/modal_edit_invoice_payment/<?php echo $row['id_compromiso_pago']; ?>');" data-original-title="Pay"> <i class="fa fa-money text-info"></i> </a>
                                            <!-- <a href="#" data-toggle="tooltip" onclick="confirm_modal('?php echo base_url(); ?>index.php?admin/invoice/delete/<php echo $row['invoice_id']; ?>');" data-original-title="Delete"> <i class="fa fa-trash text-danger"></i> </a>-->
                                            <?php }?>
                                        </td>
                                        <td style="text-align: center;"><?php echo $row['name'] ?></td>
                                        <td style="text-align: center;"><?php echo $row['de_pago_concepto']; ?></td>
                                        <td style="text-align: center;"><?php echo $row['nu_anio']; ?></td>
                                        <td style="text-align: center;"><?php echo $row['nu_cuota']; ?></td>                                      
                                        <td style="text-align: center;"><?php echo $this->crud_model->see_date_peru($row['fe_vencimiento']); ?></td></td>                                        
                                        <td style="text-align: center;"><?php echo $pension?></td>
                                        <td style="text-align: center;"><?php echo number_format($mora, 2, '.',',')?></td>
                                        <td style="text-align: center;"><?php echo number_format($total_pagar, 2, '.',',')?></td>
                                        <td style="text-align: center;"><?php echo $status; ?></td>
                                        <td style="text-align: center;"><?php echo $row['name_parent']; ?></td> 
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="paid">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;"><?php echo get_phrase('Title'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Description'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Method'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Amount'); ?></th>
                                    <th style="text-align: center;"><?php echo get_phrase('Date'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $this->db->where('payment_type', 'income');
                                $this->db->order_by('timestamp', 'desc');
                                $payments = $this->db->get('payment')->result_array();
                                foreach ($payments as $row):
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $count++; ?></td>
                                        <td style="text-align: center;"><?php echo $row['title']; ?></td>
                                        <td style="text-align: center;"><?php echo $row['description']; ?></td>
                                        <td style="text-align: center;">
                                            <?php
                                            if ($row['method'] == 1)
                                                echo get_phrase('Cash');
                                            if ($row['method'] == 2)
                                                echo get_phrase('Check');
                                            if ($row['method'] == 3)
                                                echo get_phrase('Card');
                                            if ($row['method'] == 'Paypal')
                                                echo 'paypal';
                                            ?>
                                        </td>
                                        <td><?php echo $this->db->get_where('settings', array('type' => 'currency'))->row()->description; ?><?php echo $row['amount']; ?></td>
                                        <td><?php echo date('d M,Y', $row['timestamp']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>	
                    </div>
                </div>
            </div>
        </div>					
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            "columns": [
              { null,
              null,
              "width": "20%" },
              null,
              null]
          });
        /*$(document).ready(function () {
            var table = $('#example').DataTable({
                "columnDefs": [
                    {"visible": false, "targets": 2}
                ],
                "order": [[2, 'asc']],
                "displayLength": 25,
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;
                    api.column(2, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                                    );

                            last = group;
                        }
                    });
                }
            });
            $('#example tbody').on('click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                    table.order([2, 'desc']).draw();
                } else {
                    table.order([2, 'asc']).draw();
                }
            });
        });*/
    });
</script>   