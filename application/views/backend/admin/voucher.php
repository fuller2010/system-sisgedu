<!--<div class="main_data">
    <div class="row">
        <div class="col-md-12">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th style="text-align: center;">No.</th>
                        <th style="text-align: center;"><div><php echo get_phrase('Title'); ?></div></th>
                        <th style="text-align: center;"><div><php echo get_phrase('Code'); ?></div></th>
                        <th style="text-align: center;"><div><php echo get_phrase('Student'); ?></div></th>
                        <th style="text-align: center;"><div><php echo get_phrase('Teacher'); ?></div></th>
                        <th style="text-align: center;"><div><php echo get_phrase('Priority'); ?></div></th>
                        <th style="text-align: center;"><div><php echo get_phrase('Options'); ?></div></th>
                    </tr>
                </thead>
                <tbody>
                    <php
                    #$teachers = $this->db->get('teacher')->result_array();
                    foreach ($data as $row){    ?>
                    <tr>
                        <td><=$row->name;?></td>
                    </tr>
                    <php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>-->
<?php
    $name_student = $this->db->get_where('student', array('student_id' => $student_id))->row()->name;
    $id_parent = $this->db->get_where('student', array('student_id' => $student_id))->row()->parent_id;
    $name_parent  = $this->db->get_where('parent', array('parent_id' => $id_parent))->row()->name;       
?>
<style>
    /*h1 {color:red;}
    p {color:blue;}*/
    /*@page { 
            size: 210mm 148mm;/*size: A5 landscape
            margin: 1cm;
        }*/
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    .colegio {
        font-weight: bold; font-size: 16px; text-transform: capitalize;
    }
    .colegio2 {
        font-size: 12px; text-transform: capitalize;
    }
</style>
<table style="width: 100%" >
    <tr>
        <td><!-- CABECERA -->
            <table style="width: 100%">
                <tr>
                    <td style="width: 70%"><!-- DATOS COLEGIO ==============================================  -->
                        <table style="width: 100%;text-align: center">
                            <tr>
                                <td class="colegio">COLEGIO PARROQUIAL NUESTRO SALVADOR</td> 
                            </tr>
                            <tr>
                                <td class="colegio2">Av. Marian Quimper 1300 - Jos&eacute; Galvez V.M.T</td> 
                            </tr>
                            <tr>
                                <td class="colegio2">Telf. 296-0272<</td> 
                            </tr>
                        </table>
                    </td>
                    <td  style="width: 30%; text-align: center"><!-- NUMERO DE LA FACTURA =============================== -->
                        <table style="width: 100%; border: 1px solid black" >
                            <tr>
                                <td style="text-align: center">R.U.C. 20147878959</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; background: #ccc">RECIBO DE INGRESOS</td>
                            </tr>
                            <tr>
                                <td style="text-align: center"><?php echo str_pad($nu_comprobante, 7, "0", STR_PAD_LEFT); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
        </td>
    </tr>
    <tr>
        <td><!-- DATOS DEL ALUMNO =================================================== -->
            <table style="width: 100%;"  border="1" cellspacing="0" cellpadding="0" >
                <tr>
                    <td><b>EMISI&Oacute;N:</b> <?php echo $this->crud_model->see_date_peru($fe_caja); ?></td> 
                </tr>
                <tr>
                    <td><b>NOMBRE:</b> <?php echo strtoupper($name_student); ?></td> 
                </tr>
                <tr>
                    <td><b>APODERADO:</b> <?php echo strtoupper($name_parent); ?></td> 
                </tr>                
            </table>
        </td>
    </tr>
    <tr>
        <td><!-- DETALLE -->
            <table style="width: 100%" cellspacing="0" cellpadding="0" border="1" >
                <thead>
                <tr>
                    <th >Concepto</th>
                    <th style="width: 12%">Vencimiento</th>
                    <th style="width: 15%">Importe</th>
                    <th style="width: 15%">Mora</th>
                    <th style="width: 15%">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total = 0;
                foreach ($deta_caja as $row_deta){ 
                    $deta = $this->db->get_where('r_compromisos_pago', array('id_compromiso_pago' => $row_deta->id_compromiso_pago))->row(); 
                    $fe_vencimiento = $deta->fe_vencimiento;                     
                    $pension = $deta->nu_anio."-".STR_PAD($deta->nu_cuota,2, "0", STR_PAD_LEFT); 
                    ?>
                <tr>
                    <td><?=$row_deta->de_pago_concepto . " ". $pension?></td>
                    <td><?=$this->crud_model->see_date_peru($fe_vencimiento)?></td>
                    <td style="text-align: right; padding-right: 3px"><?=$row_deta->mo_bruto;?></td>
                    <td style="text-align: right; padding-right: 3px"><?=$row_deta->mo_mora; ?></td>
                    <td style="text-align: right; padding-right: 3px"><?=$row_deta->mo_neto; ?></td>
                </tr>
                <?php
                    $total += $row_deta->mo_neto;
                }
                ?>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="border-left: none;border-bottom: none">  </td>
                        <td colspan="2" style="text-align: right; padding-right: 3px">TOTAL S/</td>
                        
                        <td style="text-align: right; padding-right: 3px"><?= number_format($total, 2, '.', ','); ?></td>
                    </tr>
                </tfoot>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
