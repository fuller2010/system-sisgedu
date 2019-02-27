<?php 
#$edit_data = $this->db->get_where('invoice' , array('invoice_id' => $param2) )->result_array();

$this->db->select('r_compromisos_pago.id_compromiso_pago,
                    r_compromisos_pago.student_id,
                    student.name as name,
                    student.student_code,
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
$this->db->join('p_pago_conceptos','p_pago_conceptos.id_pago_concepto = r_compromisos_pago.id_pago_concepto');
$this->db->where('r_compromisos_pago.in_esta',1);
$this->db->where('r_compromisos_pago.id_centro_educativo',1);
$this->db->where('r_compromisos_pago.id_compromiso_pago',$param2);
$edit_data = $this->db->get()->result_array();
                                
?>
<div class="row title">
    <div class="col-md-12">
        <div class="panel panel-info" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title" >                    
                    <font color="white"><?php echo get_phrase('payment-commitment') ?></font>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tab-pane box active" id="edit" >
    <div class="box-content">
        <?php foreach($edit_data as $row): 
            
            $dias = $this->crud_model->expiration_day($row['fe_vencimiento']);
            $pension = $row['mo_cuota_pagar'];
            $mora = $this->crud_model->default_rate($dias);
            $total_pagar = $pension + $mora;
            $num = $this->crud_model->get_numero_comprobante($this->session->userdata('VS_idCentroEducativo'), 1);
            $this->session->userdata('VS_idCentroEducativo')
            ?>
        <?php echo form_open(base_url() . 'index.php?admin/invoice_caja/'.$row['id_compromiso_pago'], array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_blank'));?>
        <br><br>
                <div class="form-group">
                    <input type="hidden" name="id_compromiso_pago" id="id_compromiso_pago" value="<?php echo $row["id_compromiso_pago"];?>">
                    <input type="hidden" name="id_tipo_docu_caja"  id="id_tipo_docu_caja"  value="1">
                    <input type="hidden" name="student_id" id="student_id" value="<?php echo $row["student_id"];?>">
                    <input type="hidden" name="id_concep_pago" id="id_concep_pago" value="<?php echo $row["id_pago_concepto"];?>">
                    <input type="hidden" name="hdn_nu_anio" id="hdn_nu_anio" value="<?php echo $row["nu_anio"];?>">
                    <input type="hidden" name="hdn_cuota" id="hdn_cuota" value="<?php echo $row["nu_cuota"];?>">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Student');?>:</label>
                    <label class="col-sm-5 control-label"><?php echo $row["name"];?></label>                    
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('concept');?>:</label>
                    <!--<label class="col-sm-5 control-label"><php echo $row["de_pago_concepto"];?></label>-->    
                    <div class="col-sm-5">
                        <input type="text" class="form-control" style="text-align: right" name="txt_concept" value="<?php echo $row['de_pago_concepto'];?>" readonly=""/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('instalment');?></label>
                    <label class="col-sm-5 control-label"><?php echo $row["nu_anio"] ."-".str_pad($row["nu_cuota"], 2, "0", STR_PAD_LEFT);?></label>  
<!--                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="description" value="?php echo $row['description'];?>"/>
                    </div>-->
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('expiration-date');?></label>
                    <label class="col-sm-5 control-label"><?php echo $this->crud_model->see_date_peru($row['fe_vencimiento']);?></label>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Pension');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="txt_pension" value="<?php echo $row['mo_cuota_pagar'];?>" style="text-align: right"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('default-rate');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="txt_mora" value="<?php echo $mora;?> " style="text-align: right"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('Total');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="txt_total" value="<?php echo $total_pagar;?>" style="text-align: right"/>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('Save');?></button>
                  </div>
                </div>
        </form>
        <?php endforeach;?>
    </div>
</div>