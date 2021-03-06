<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xhr_ajax extends CI_Controller {

    public function ajax_get_mitra_by_user()
    {

        $this->db->select('data_activity.create_by, count(data_activity.ID) as jumlah');
        $this->db->join('data_mitra','data_mitra.id_mitra=data_activity.member_ID','right');
        $this->db->join('klasifikasi_member k1','k1.id_mitra=data_mitra.id_mitra','left');
        $this->db->join('klasifikasi_member k2','k2.id_mitra=data_mitra.id_mitra and k1.id<k2.id','left outer');
        $this->db->join('data_klasifikasi','data_klasifikasi.id=k1.id_klasifikasi','left');
        $this->db->join('data_activity a1','a1.member_ID=data_mitra.id_mitra','left');
        $this->db->join('data_activity a2','a2.member_ID=data_mitra.id_mitra and a1.ID<a2.ID and ISNULL(a1.delete_at)','left outer');
        $this->db->join('data_respon','data_respon.id=a1.id_respon','left');
        $this->db->where('k2.id',NULL);
        $this->db->where('a2.ID',NULL);
        
        if($this->session->userdata('followup_date_activity')!=""){
            $daten = explode(" - ", $this->session->userdata('followup_date_activity'));
            $date_start = $daten[0];
            $date_end = $daten[1];
            $this->db->where('data_activity.create_at>=',date_format(date_create($date_start),"Y-m-d"));
            $this->db->where('data_activity.create_at<=',date_format(date_create($date_end),"Y-m-d"));
        }
        if($this->session->userdata('followup_date_join')!=""){
            $daten = explode(" - ", $this->session->userdata('followup_date_join'));
            $date_start = $daten[0];
            $date_end = $daten[1];
            $this->db->where('data_mitra.join_date>=',date_format(date_create($date_start),"Y-m-d"));
            $this->db->where('data_mitra.join_date<=',date_format(date_create($date_end),"Y-m-d"));
        }
        if($this->session->userdata('followup_brand_name')!=""){
            $this->db->like('data_mitra.brand_name',$this->session->userdata('followup_brand_name'),'both');
        }
        if($this->session->userdata('followup_prefix')!=""){
            $this->db->like('data_mitra.prefix',$this->session->userdata('followup_prefix'),'both');
        }

        if($this->session->userdata('followup_type')!=""){
            $this->db->like('data_mitra.type',$this->session->userdata('followup_type'),'both');
        }
        if($this->session->userdata('followup_topup')!=""){
            $this->db->like('data_mitra.topup',$this->session->userdata('followup_topup'),'both');
        }
        if($this->session->userdata('followup_trx')!=""){
            $this->db->like('data_mitra.trx',$this->session->userdata('followup_trx'),'both');
        }
        if($this->session->userdata('followup_respon')!=""){
            $this->db->like('data_activity.id_respon',$this->session->userdata('followup_respon'),'both');
        }
        if($this->session->userdata('followup_province')!=""){
            $this->db->like('data_mitra.province',$this->session->userdata('followup_province'),'both');
        }
        if($this->session->userdata('followup_klasifikasi')!=""){
            if($this->session->userdata('followup_klasifikasi')==0){
                $this->db->where('data_klasifikasi.id',NULL);
            }else{
                $this->db->where('data_klasifikasi.id',$this->session->userdata('followup_klasifikasi'));
            }
        }
        if($this->session->userdata('followup_status')!=""){
            if($this->session->userdata('followup_status')!="all"){
                $this->db->like('data_mitra.status',$this->session->userdata('followup_status'),'both');
            }
        }else{
            $this->db->like('data_mitra.status','active','both');
        }
        $this->db->group_by('data_activity.create_by');


        $data['followup_by'] = $this->db->get('data_activity')->result_array();
        
          foreach ($data['followup_by'] as $key) {
            if($key['create_by']!=NULL){
              ?>
              <span class="btn"><?php echo $this->general->get_user($key['create_by'])." : ".$key['jumlah'];?></span>
              <?php 
            }
          }
    }

    public function ajax_get_mitra_by_klasifikasi()
    {   
        $this->db->select('data_klasifikasi.klasifikasi, count(data_mitra.id_mitra) as jumlah');
        $this->db->join('klasifikasi_member k1','k1.id_mitra=data_mitra.id_mitra','left');
        $this->db->join('klasifikasi_member k2','k2.id_mitra=data_mitra.id_mitra and k1.id<k2.id','left outer');
        $this->db->join('data_klasifikasi','data_klasifikasi.id=k1.id_klasifikasi','left');
        $this->db->join('data_activity a1','a1.member_ID=data_mitra.id_mitra','left');
        $this->db->join('data_activity a2','a2.member_ID=data_mitra.id_mitra and a1.ID<a2.ID and ISNULL(a1.delete_at)','left outer');
        $this->db->join('data_respon','data_respon.id=a1.id_respon','left');
        $this->db->where('k2.id',NULL);
        $this->db->where('a2.ID',NULL);
        
        if($this->session->userdata('followup_date_activity')!=""){
            $daten = explode(" - ", $this->session->userdata('followup_date_activity'));
            $date_start = $daten[0];
            $date_end = $daten[1];
            $this->db->where('a1.create_at>=',date_format(date_create($date_start),"Y-m-d"));
            $this->db->where('a1.create_at<=',date_format(date_create($date_end),"Y-m-d"));
        }
        if($this->session->userdata('followup_date_join')!=""){
            $daten = explode(" - ", $this->session->userdata('followup_date_join'));
            $date_start = $daten[0];
            $date_end = $daten[1];
            $this->db->where('data_mitra.join_date>=',date_format(date_create($date_start),"Y-m-d"));
            $this->db->where('data_mitra.join_date<=',date_format(date_create($date_end),"Y-m-d"));
        }
        if($this->session->userdata('followup_brand_name')!=""){
            $this->db->like('data_mitra.brand_name',$this->session->userdata('followup_brand_name'),'both');
        }
        if($this->session->userdata('followup_prefix')!=""){
            $this->db->like('data_mitra.prefix',$this->session->userdata('followup_prefix'),'both');
        }

        if($this->session->userdata('followup_type')!=""){
            $this->db->like('data_mitra.type',$this->session->userdata('followup_type'),'both');
        }
        if($this->session->userdata('followup_topup')!=""){
            $this->db->like('data_mitra.topup',$this->session->userdata('followup_topup'),'both');
        }
        if($this->session->userdata('followup_trx')!=""){
            $this->db->like('data_mitra.trx',$this->session->userdata('followup_trx'),'both');
        }
        if($this->session->userdata('followup_respon')!=""){
            $this->db->like('data_activity.id_respon',$this->session->userdata('followup_respon'),'both');
        }
        if($this->session->userdata('followup_province')!=""){
            $this->db->like('data_mitra.province',$this->session->userdata('followup_province'),'both');
        }
        if($this->session->userdata('followup_klasifikasi')!=""){
            if($this->session->userdata('followup_klasifikasi')==0){
                $this->db->where('data_klasifikasi.id',NULL);
            }else{
                $this->db->where('data_klasifikasi.id',$this->session->userdata('followup_klasifikasi'));
            }
        }
        if($this->session->userdata('followup_status')!=""){
            if($this->session->userdata('followup_status')!="all"){
                $this->db->like('data_mitra.status',$this->session->userdata('followup_status'),'both');
            }
        }else{
            $this->db->like('data_mitra.status','active','both');
        }
        $this->db->group_by('k1.id_klasifikasi');


        $data['followup_by_klasifikasi'] = $this->db->get('data_mitra')->result_array();
        
        //die("<pre>".print_r($data['followup_by_klasifikasi'],1)."</pre>");

      foreach ($data['followup_by_klasifikasi'] as $key) {
          ?>
          <span class="btn"><?php echo (($key['klasifikasi']==NULL)?"Non Data":$key['klasifikasi'])." : ".$key['jumlah'];?></span>
          <?php 
      }


    }

    public function ajax_get_mitra()
    {
        $term = $this->input->post('term');
        $this->db->select("id_mitra as id, concat(brand_name,' (',prefix,')') as value, prefix, type");
        $this->db->group_start();
        $this->db->like("concat(brand_name,' (',prefix,')')",$term,'both');
        $this->db->group_end();
        $this->db->where('status','active');
        $this->db->limit(7);
        $a = $this->db->get('data_mitra')->result_array();
        echo json_encode($a);
    }
    public function update_action_done()
    {
        $id = $this->input->post('id');

        $this->db->where('id',$id);
        $dat = $this->db->get('actionsys')->row_array();

        $data['comment'] = $dat['comment']."\r\nDone by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        $data['done_at'] = date("Y-m-d H:i:s");
        $data['status'] = 2;
        $this->db->where('id',$id);
        $this->db->update('actionsys',$data);


        echo "OK";
    }
    public function update_issued_manual_done()
    {
        $id = $this->input->post('id');

        $this->db->where('id',$id);
        $dat = $this->db->get('actionsys')->row_array();

        $data['comment'] = $dat['comment']."\r\nDone by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        $data['done_at'] = date("Y-m-d H:i:s");
        $data['nota_airline'] = $this->input->post('nota_airlines');
        $data['nota_member'] = $this->input->post('nota_member');
        $data['status'] = 2;
        $this->db->where('id',$id);
        $this->db->update('actionsys',$data);


        echo "OK";
    }
    public function update_rebook_done()
    {
        $data = $this->input->post();

        $this->db->where('id',$data['id']);
        $dat = $this->db->get('actionsys')->row_array();

        if($data['rebook_status']==2){
            $data['comment'] = $dat['comment']."\r\nDone by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
            $data['done_at'] = date("Y-m-d H:i:s");
            $data['status'] = 2;
            $data['rebook_status'] = 2;
            $data['assign_view'] = 1;
            $data['rebook_process'] = date("Y-m-d H:i:s");
        }
        if($data['rebook_status']==1 or $data['rebook_status']==0){
            $data['comment'] = $dat['comment']."\r\nUpdate Rebook Status to ".$data['rebook_status']." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
            $data['done_at'] = NULL;
            $data['status'] = $data['rebook_status'];
            $data['assign_view'] = $data['rebook_status'];
            if($data['rebook_status']==0){
                $data['id_assign'] = 0;
                $data['rebook_process'] = NULL;
            }else{
                $data['rebook_process'] = date("Y-m-d H:i:s");
            }
        }
        $data['act_budget'] = $this->input->post('act_budget');
        $this->db->where('id',$data['id']);
        $this->db->update('actionsys',$data);


        echo "OK";
    }
    public function update_refund_done()
    {
        $data = $this->input->post();

        $this->db->where('id',$data['id']);
        $dat = $this->db->get('actionsys')->row_array();

        $data['refund_total_cost'] = $this->input->post('refund_total_cost');
        $data['refund_airline_status'] = $this->input->post('refund_airline_status');
        $data['refund_member_status'] = $this->input->post('refund_member_status');
        $data['refund_airline_date'] = date_format(date_create($this->input->post('refund_airline_date')),"Y-m-d");
        $data['refund_member_date'] = date_format(date_create($this->input->post('refund_member_date')),"Y-m-d");

        $data['comment'] = $dat['comment'];
        if($dat['refund_total_cost']!=$this->input->post('refund_total_cost')){
            $data['comment'] = $data['comment']."\r\nUpdate Refund Total Cost from ".$dat['refund_total_cost']." to ".$data['refund_total_cost']." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }
        if($dat['refund_airline_status']!=$this->input->post('refund_airline_status')){
            $data['comment'] = $data['comment']."\r\nUpdate Refund Airline Status from ".$this->general->get_solved($dat['refund_airline_status'])." to ".$this->general->get_solved($data['refund_airline_status'])." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }
        if($dat['refund_member_status']!=$this->input->post('refund_member_status')){
            $data['comment'] = $data['comment']."\r\nUpdate Refund Member Status from ".$this->general->get_solved($dat['refund_member_status'])." to ".$this->general->get_solved($data['refund_member_status'])." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }
        if($dat['refund_member_date']!=$this->input->post('refund_member_date')){
            $data['comment'] = $data['comment']."\r\nUpdate Refund Member Date Status from ".$dat['refund_member_date']." to ".$data['refund_member_date']." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }
        if($dat['refund_airline_date']!=$this->input->post('refund_airline_date')){
            $data['comment'] = $data['comment']."\r\nUpdate Refund Airline Date Status from ".$dat['refund_airline_date']." to ".$data['refund_airline_date']." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }

        if($data['refund_member_status']==0){
            $data['status'] = 0;
            $data['id_assign'] = 0;
            $data['assign_view'] = 0;
            $data['user_view'] = 1;
        }else{            
            $data['comment'] = $dat['comment']."\r\nDone by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
            $data['done_at'] = date("Y-m-d H:i:s");
            $data['status'] = 2;
            $data['id_assign'] = $this->session->userdata('id');
            $data['assign_view'] = 1;
            $data['user_view'] = 0;
        }

        if($dat['comment']==$data['comment']){
            unset($data['comment']);
        }


        $this->db->where('id',$data['id']);
        $this->db->update('actionsys',$data);


        echo "OK";
    }
    public function update_void_done()
    {
        $data = $this->input->post();

        $this->db->where('id',$data['id']);
        $dat = $this->db->get('actionsys')->row_array();

        $data['est_budget'] = $this->input->post('est_budget');

        $data['act_budget'] = $data['est_budget'];
        $data['comment'] = $dat['comment'];
        if($dat['est_budget']!=$this->input->post('est_budget')){
            $data['comment'] = $data['comment']."\r\nUpdate Void Amount from ".$dat['est_budget']." to ".$data['est_budget']." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }
        if($dat['status']!=$this->input->post('status')){
            $data['comment'] = $data['comment']."\r\nUpdate Void Status from ".$this->general->get_status($dat['status'])." to ".$this->general->get_status($data['status'])." by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        }        

        if($data['status']==0){
            $data['id_assign'] = 0;
            $data['assign_view'] = 0;
            $data['user_view'] = 1;
        }elseif ($data['status']==1){            
            $data['id_assign'] = $this->session->userdata('id');
            $data['assign_view'] = 1;
            $data['user_view'] = 1;
        }else{
            $data['id_assign'] = $this->session->userdata('id');
            $data['assign_view'] = 1;
            $data['user_view'] = 1;
            $data['comment'] = $data['comment']."\r\nDone by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
            $data['done_at'] = date("Y-m-d H:i:s");
        }

        if($dat['comment']==$data['comment']){
            unset($data['comment']);
        }


        $this->db->where('id',$data['id']);
        $this->db->update('actionsys',$data);


        echo "OK";
    }
    public function lookup_code()
    {
        $value = $this->input->post('code');
        $dbs = $this->load->database('dbpointer',TRUE);
        $dbs->where('kode_booking',$value);
        $data['ar_booking'] = $dbs->get('ar_booking')->row_array();
        if($data['ar_booking']!=null){
            $dbs->select('mitra.prefix,company.brand_name,type.type');
            $dbs->join('company','company.id_mitra=mitra.id_mitra','left');
            $dbs->join('type','type.id_type=mitra.id_type','left');
            $dbs->where('mitra.id_mitra',$data['ar_booking']['id_mitra']);
            $data['mitra'] = $dbs->get('mitra')->row_array();
            $dbs->where('id_booking',$data['ar_booking']['id']);
            $data['ar_booking_pnr'] = $dbs->get('ar_booking_pnr')->result_array();
            $data['json_data'] = json_encode($data);
        }
        echo (json_encode($data));

    }
    public function update_action_open()
    {
        $id = $this->input->post('id');

        $this->db->where('id',$id);
        $dat = $this->db->get('actionsys')->row_array();

        $data['comment'] = $dat['comment']."\r\nOpen by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');

        $data['status'] = 0;
        $data['assign_view'] = 0;
        $data['id_assign'] = 0;
        $this->db->where('id',$id);
        $this->db->update('actionsys',$data);
        echo "OK";
    }
    public function ajax_get_actionsys_data($id)
    {
        $this->db->where('id',$id);
        $data = $this->db->get('actionsys');
        $data['act_budget'] = "Rp. ".number_format($data['act_budget']);
        echo json_encode($data);
    }

    public function ajax_get_actionsys_data_open()
    {
        $id = $this->input->post('id');
        $this->db->where('id',$id);
        $data = $this->db->get('actionsys')->row_array();
        $data['brand_name'] = $this->general->get_member($data['id_mitra'],1);
        $data['airline'] = $this->general->get_vendor($data['vendor']);
        if($data['status']==0){
            $data['act_budget_rp'] = "Rp. ".number_format($data['act_budget']);
            $dat['status'] = "OPEN";
            $dat['data'] = $data;

            $upt['comment'] = $data['comment']."\r\nHold by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');

            if($data['id_assign']==0 or $data['id_assign']==$this->session->userdata('id')){
                $upt['id_assign'] = $this->session->userdata('id');
            }else{
                $upt['id_user'] = $this->session->userdata('id');
            }
            $upt['assign_view'] = 1;
            //$upt['status'] = 1;
            $this->db->where('id',$id);
            $this->db->update('actionsys',$upt);
            echo json_encode($dat);
        }elseif($data['status']==1){
            $data['act_budget_rp'] = "Rp. ".number_format($data['act_budget']);
            $dat['status'] = "VIEW";
            $dat['data'] = $data;
            $dat['by'] = $this->general->get_user($data['id_assign']);
            echo json_encode($dat);
        }elseif($data['status']==2){
            $dat['status'] = "FINISH";
            $dat['data'] = $data;
            $dat['by'] = $this->general->get_user($data['id_assign']);
            echo json_encode($dat);
        }
    }

    public function ajax_get_pending_action()
    {
        $data = array();
        $this->db->select('actionsys.*,flowsys.assign_user');
        $this->db->join('flowsys','flowsys.id=actionsys.id_flowsys','left');
        // $this->db->group_start();
        // $this->db->like('flowsys.assign_user',','.$this->session->userdata('id').',','both');
        // $this->db->or_like('flowsys.assign_user',$this->session->userdata('id').',','after');
        // $this->db->or_like('flowsys.assign_user',','.$this->session->userdata('id'),'before');
        // $this->db->or_like('actionsys.id_user',$this->session->userdata('id'));
        // $this->db->or_like('actionsys.id_assign',$this->session->userdata('id'));
        // $this->db->group_end();
        $this->db->where_in('status',array(0,1));
        $data['action'] = $this->db->get('actionsys')->result_array();

        $d = array();
        foreach ($data['action'] as $key) {
            $assign_user = explode(",", $key['assign_user']);
            if(in_array($this->session->userdata('id'), $assign_user)){
                $d[] = $key;
            }
        }

        $data['action'] = $d;
        //echo $this->db->last_query();
        $jumlah = sizeof($data['action']);
        $data['muncul'] = 0;
        if($this->session->userdata('pending')<$jumlah){
            $this->session->set_userdata('pending',$jumlah);
            $data['muncul'] = 1;
        }
        $this->session->set_userdata('pending',$jumlah);
        echo json_encode($data);
    }

    public function ajax_actionsys_save()
    {
        $data = $this->input->post();
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['id_ticket'] = uniqid();
        $data['comment'] = "Created by Human (".date("Y-m-d H:i:s").") ".$this->session->userdata('nama');
        if(empty($data['tgl_info'])){
            $data['tgl_info'] = date("Y-m-d H:i:s");
        }
        $data['id_user'] = $this->session->userdata('id');
        $this->db->insert('actionsys',$data);
        if($this->db->insert_id()!=""){
            echo json_encode(array('status'=>'OK','msg'=>'Request Saved!'));
        }else{
            echo json_encode(array('status'=>'ER','msg'=>'Request failed to save'));
        }
    }
//------------------------------------------------------------------------------

  public function ajax_get_klasifikasi(){
    $this->general->logging();
		$data = $this->input->post();
        $this->db->where('id_mitra',$data['id']);
        $datklas['getMemberKlas'] = $this->db->get('data_mitra')->row_array();
        $datklas['getKlasifikasi'] = $this->db->get('data_klasifikasi')->result_array();
		$this->db->where('id_mitra',$data['id']);
        $this->db->order_by('id','desc');
        $balik = $this->db->get('data_klasifikasi')->row_array();
        $datklas['lastKlasifikasi'] = empty($balik)?0:$balik->id_klasifikasi; 
		print_r(json_encode($datklas));
  }

//------------------------------------------------------------------------------

public function ajax_save_klasifikasi()
{
  $this->general->logging();
  $data = $this->input->post();
  $data['tgl_update'] = date("Y-m-d H:i:s");
  $data['created_by'] = $this->session->userdata('id');
  $this->db->insert('klasifikasi_member',$data);
  //print_r(json_encode($data));
  echo json_encode(array('jenis'=>'klasifikasi','brand_name'=>$this->db->where('id_mitra',$data['id_mitra'])->get('data_mitra')->row_array()['brand_name']));
}

//------------------------------------------------------------------------------

   public function cek_deposit()
    {
        $jumlah_muncul = $this->session->userdata('saldo');
        $muncul = 0;
        $this->db->select('cek_deposit.*,vendor.nama,vendor.min_first,vendor.min_second,vendor.min_third');
        $this->db->join('vendor','vendor.id=cek_deposit.id','left');
        $this->db->order_by('airline','asc');
        $data = $this->db->get('cek_deposit');
        $data = $data->result_array();
        $baru = array();
        foreach ($data as $key) {
            $muncul = 0;
            if($key['saldo']>0 or $key['saldo']<0){
                if($key['saldo']<$key['min_first']){
                    $muncul = 1;
                }
                if($key['saldo']<$key['min_second']){
                    $muncul = 2;
                }
                if($key['saldo']<$key['min_third']){
                    $muncul = 3;
                }
            }

            if($muncul>0 and $muncul != $jumlah_muncul){
                $jumlah_muncul = $muncul;
            }

            $baru[] = array('code'=>$key['code'],
                            'airline'=>$key['airline'],
                            'id'=>$key['id'],
                            'saldo'=>'Rp. '.number_format($key['saldo'],2),
                            'muncul'=>$muncul);
        }


        $all = array();

        $all['muncul'] = 0;
        if($this->session->userdata('saldo')<$jumlah_muncul){
            $this->session->set_userdata('saldo',$jumlah_muncul);
            $all['muncul'] = 1;
        }
        $this->session->set_userdata('saldo',$jumlah_muncul);
        $all['saldo'] = $baru;
        echo json_encode($all);
    }
//------------------------------------------------------------------------------

   public function cron_cek_deposit()
    {
        $this->db->select('cek_deposit.*,vendor.nama,vendor.min_first,vendor.min_second,vendor.min_third');
        $this->db->join('vendor','vendor.id=cek_deposit.id','left');
        $this->db->order_by('airline','asc');
        $data = $this->db->get('cek_deposit');
        $data = $data->result_array();
        $baru = array();
        foreach ($data as $key) {
            $muncul = 0;
            if($key['saldo']<$key['min_first']){
                $muncul = 1;
            }
            if($key['saldo']<$key['min_second']){
                $muncul = 2;
            }
            if($key['saldo']<$key['min_third']){
                $muncul = 3;
            }

            if($muncul>0){


                $this->db->where_in('actionsys.id_flowsys',array(7,8,9));
                $this->db->where('actionsys.vendor',$key['id']);
                $this->db->where('actionsys.status','0');
                $cek = $this->db->get('actionsys')->result_array();
                if(sizeof($cek)==0){
                    $input = array();
                    $input['id_user'] = 0;
                    $input['id_flowsys'] = 7;
                    $input['info'] = "Alert 1 Top Up Saldo Vendor ".$key['nama'];
                    $input['user_view'] = 0;
                    $input['assign_view'] = 0;
                    $input['comment'] = $this->general->comment_msg("System");
                    $input['status'] = 0;
                    $input['vendor'] = $key['id'];
                    $input['created_at'] = date("Y-m-d H:i:s");
                    $input['id_ticket'] = "#".uniqid();
                    $this->db->insert('actionsys',$input);
                    $log['id_user'] = 0;
                    $log['info'] = "Create alert 1 top up saldo vendor ".$key['nama'];
                    $log['created_at'] = date("Y-m-d H:i:s");
                    $this->db->insert('actionsyslog',$log);
                }else{
                    if($muncul==2){

                        $this->db->where_in('actionsys.id_flowsys',array(7,8,9));
                        $this->db->where('actionsys.vendor',$key['id']);
                        $this->db->where('actionsys.status','0');
                        $cek2 = $this->db->get('actionsys')->row_array();
                        if(empty($cek2)){
                            
                            $input['id_user'] = 0;
                            $input['id_flowsys'] = 8;
                            $input['info'] = "Alert 2 Top Up Saldo Vendor ".$key['nama'];
                            $input['user_view'] = 0;
                            $input['assign_view'] = 0;
                            $input['comment'] = $this->general->comment_msg("System");
                            $input['status'] = 0;
                            $input['created_at'] = date("Y-m-d H:i:s");
                            $input['id_ticket'] = "#".uniqid();

                            $this->db->where('actionsys.id_flowsys',7);
                            $this->db->where('actionsys.vendor',$key['id']);
                            $this->db->where('actionsys.status','0');
                            $this->db->update('actionsys',$input);

                            $log['id_user'] = 0;
                            $log['info'] = "Create alert 2 top up saldo vendor ".$key['nama'];
                            $log['created_at'] = date("Y-m-d H:i:s");
                            $this->db->insert('actionsyslog',$log);
                        }
                    }
                    if($muncul==3){

                        $this->db->where_in('actionsys.id_flowsys',array(7,8,9));
                        $this->db->where('actionsys.vendor',$key['id']);
                        $this->db->where('actionsys.status','0');
                        $cek2 = $this->db->get('actionsys')->row_array();
                        if(empty($cek2)){

                            $input['id_user'] = 0;
                            $input['id_flowsys'] = 9;
                            $input['info'] = "Alert 3 Top Up Saldo Vendor ".$key['nama'];
                            $input['user_view'] = 0;
                            $input['assign_view'] = 0;
                            $input['comment'] = $this->general->comment_msg("System");
                            $input['status'] = 0;
                            $input['created_at'] = date("Y-m-d H:i:s");
                            $input['id_ticket'] = "#".uniqid();

                            $this->db->where('actionsys.id_flowsys',8);
                            $this->db->where('actionsys.vendor',$key['id']);
                            $this->db->where('actionsys.status','0');
                            $this->db->update('actionsys',$input);

                            $log['id_user'] = 0;
                            $log['info'] = "Create alert 3 top up saldo vendor ".$key['nama'];
                            $log['created_at'] = date("Y-m-d H:i:s");
                            $this->db->insert('actionsyslog',$log);
                        }
                    }
                }
            }

            $baru[] = array('code'=>$key['code'],
                            'airline'=>$key['airline'],
                            'id'=>$key['id'],
                            'saldo'=>'Rp. '.number_format($key['saldo'],2),
                            'muncul'=>$muncul);
        }

        echo json_encode($baru);
    }

    public function change_status()
    {
        $this->general->logging();
        $this->db->where('id',$this->session->userdata('id'));
        $this->db->update('data_user',array('status'=>$this->input->post('status')));
        echo $this->input->post('status');
    }
	public function issued_log_data()
	{
		$datax = $this->db->get('temp_issued_today');
		$datax = $datax->result_array();
		$hasil['data'] = $datax;
		$this->db->select('def_kode_error.nama,data_mitra.prefix,data_mitra.brand_name,data_all_error.*');
		$this->db->join('data_mitra','data_mitra.id_mitra=data_all_error.id_mitra','left');
		$this->db->join('def_kode_error','def_kode_error.kode_error=data_all_error.kasus','left');
        $this->db->where('updated_at',NULL);
		$this->db->order_by('id','desc');
		$dataz = $this->db->get('data_all_error');
		$dataz = $dataz->result_array();

        $hasil['muncul'] = 0;
        if($this->session->userdata('revert')<sizeof($dataz)){
            $this->session->set_userdata('revert',sizeof($dataz));
            $hasil['muncul'] = 1;
        }
        $this->session->set_userdata('revert',sizeof($dataz));

		$hasil['revert'] = $dataz;
		$this->session->set_userdata('revert_data',sizeof($dataz));
		$datay = $this->db->get('temp_processing_issued');
		$datay = $datay->result_array();

        $newdatay = array();
        foreach ($datay as $key) {
            $strStart = $key['waktu']; 
            $strEnd   = date("Y-m-d H:i:s"); 

            $dteStart = new DateTime($strStart); 
            $dteEnd   = new DateTime($strEnd); 

            $dteDiff  = $dteStart->diff($dteEnd); 

            $pre="";
            if($dteDiff->format("%i")>0){
                if($dteStart>$dteEnd){
                    $pre = "-";
                }
            }


            $newdatay[] = $key+array('diff'=>$pre.$dteDiff->format("%i"));
        }
        $datay = $newdatay;

		$hasil['process'] = $datay;
		echo json_encode($hasil);
	}
	public function ajax_get_activity($id_mitra)
	{
        $this->general->logging();
	?>
              <table class="table table-bordered table-striped" id="TBL_<?php echo $id_mitra;?>">
              <tr>
                <th style="width:10px;">TicketID</th>
                <th>Type</th>
                <th>Respon</th>
                <th>Note / Response / Reason</th>
                <th>PIC</th>
                <th>DateTime</th>
                <th class="last" style="width:20px;">Action</th>
              </tr>

              <?php

              $this->db->where('member_ID',$id_mitra);
              $this->db->where('delete_by',NULL);
              $this->db->where('delete_at',NULL);
              $this->db->order_by('create_at','desc');
              $aa = $this->db->get('data_activity');
              $aa = $aa->result_array();
              foreach ($aa as $koka) {
              ?>
              <tr id="detail_<?php echo $koka['ID'];?>">
                <td>#<?php echo $koka['ID'];?></td>
                <td><?php echo $koka['type'];?></td>
                <td><?php echo $this->general->get_respon($koka['id_respon']);?></td>
                <td style="max-width:300px;"><?php echo $koka['reason'];?></td>
                <td><?php echo $this->general->get_user($koka['create_by']);?></td>
                <td><?php echo $koka['create_at'];?></td>
                <td class="last"><a style="cursor:pointer" onclick="del_followup(<?php echo $koka['ID'];?>)"><i class="fa fa-times"></i></a></td>
              </tr>
              <?php
              }
              ?>
              </table>
              <?php
	}
	public function ajax_save_act()
	{
		$this->general->logging();
		$data = $this->input->post();
		$data['create_at'] = date("Y-m-d H:i:s");
		$data['create_by'] = $this->session->userdata('id');
		$this->db->insert('data_activity',$data);
		$id = $this->db->insert_id();
		$dat['create_at'] = $data['create_at'];
		$dat['ID'] = $id;
		$dat['PIC'] = $this->general->get_user($this->session->userdata('id'));
		print_r(json_encode($dat));
	}
	public function ajax_get_profiling()
	{
		$this->general->logging();
		$data = $this->input->post();
		$this->db->where('id_mitra',$data['id']);
		$dat['profiling'] = $this->db->get('data_mitra')->row_array();

        $this->db->join('data_klasifikasi','data_klasifikasi.id=klasifikasi_member.id_klasifikasi','left');
        $this->db->where('id_mitra',$data['id']);
        $this->db->order_by('klasifikasi_member.id','desc');
        $class = $this->db->get('klasifikasi_member')->row_array();
        $dat['profiling']['klasifikasi'] = !empty($class['klasifikasi'])?$class['klasifikasi']:"No Data";
		$dat['profiling']['klasifikasi_id'] = !empty($class['klasifikasi'])?$class['id_klasifikasi']:0;
        $this->db->where('id_mitra',$data['id']);
        $data_detail = $this->db->get('data_detail_mitra')->row_array();
        if(empty($data_detail)){
            $data_detail = array('bank'=>'No Data',
                                    'tipe'=>'No Data',
                                    'lastsystem'=>'No Data',
                                    'info'=>'No Data',
                                    'otherinfo'=>'');
        }
        $dat['detail_mitra'] = $data_detail;
        $dat['all_tipe'] = $this->db->where('jenis','tipe')->get('data_profiling')->result_array();
        $dat['all_lastsystem'] = $this->db->where('jenis','lastsystem')->get('data_profiling')->result_array();
        $dat['all_info'] = $this->db->where('jenis','info')->get('data_profiling')->result_array();
        $dat['all_bank'] = $this->db->where('jenis','bank')->get('data_profiling')->result_array();
        $dat['all_klasifikasi'] = $this->db->get('data_klasifikasi')->result_array();
        $dat['response'] = $this->db->get('data_respon')->result_array();
		print_r(json_encode($dat));
	}
    public function save_profiling_new($new="")
    {
        $id_mitra = $this->input->post('id_mitra');
        $jenis = $this->input->post('jenis');
        $prof = $this->input->post('prof');

        $this->db->where('id_mitra',$id_mitra);
        $a = $this->db->get('data_detail_mitra')->row_array();
        if(empty($a)){
            $data['id_mitra'] = $id_mitra;
            $data[$jenis] = $prof;
            $a = $this->db->insert('data_detail_mitra',$data);
        }else{
            $this->db->where('id_mitra',$id_mitra);
            $this->db->update('data_detail_mitra',array($jenis=>$prof));
        }
        if($new=="new"){
            $data['jenis'] = $jenis; 
            $data['data'] = $prof;
            $a = $this->db->insert('data_profiling',$data);
        }
        echo json_encode(array('jenis'=>$jenis,'brand_name'=>$this->db->where('id_mitra',$id_mitra)->get('data_mitra')->row_array()['brand_name']));

    }
	public function get_solve_note_option()
	{
    $this->general->logging();
		$this->db->order_by('id','asc');
		$a = $this->db->get('def_solve_note');
		$a = $a->result_array();
		foreach ($a as $key) {
			echo "<option value='".$key['isi']."'>".$key['isi']."</option>";
		}
	}

    public function get_user_assign()
    {
        $data = array();
        // $a = $this->db->get('division')->result_array();
        // foreach ($a as $key) {
        //     $data[] = array('id'=>$key['id'],'parentid'=>-1,'text'=>$key['name'],'value'=>'!'.$key['id'].'!');
        //     $x = $this->db->where('id_division',$key['id'])->get('level')->result_array();
        //     foreach ($x as $kay) {
        //         $data[] = array('id',$key['id'].$kay['id'],'parentid'=>$key['id'],'text'=>$kay['name'],'value'=>'@'.$kay['id'].'@');
        //         // $s = $this->db->where('id_division',$key['id'])->where('id_level',$kay['id'])->get('data_user')->result_array();
        //         // foreach ($s as $kuy) {
        //         //     $data[] = array('id'=>$key['id'].$kay['id'].$kuy['ID'],'parentid'=>$key['id'].$kay['id'],'text'=>$kuy['name'],'value'=>'|'.$kuy['ID'].'|');
        //         // }
        //     }
        // }
        $a = $this->db->get('level')->result_array();
        foreach ($a as $key) {
            $data[] = array('id'=>"DIVLEV".$key['id'].$key['id_division'].$key['id_division'].$key['id_division'],'parentid'=>($key['id_level']==0)?"DIV".$key['id_division'].$key['id_division'].$key['id_division']:("DIVLEV".$key['id_level'].$key['id_division'].$key['id_division'].$key['id_division']),'text'=>$key['name'],'value'=>'!'.$key['id'].'!');
        }
        $a = $this->db->get('division')->result_array();
        foreach ($a as $key) {
            $data[] = array('id'=>"DIV".$key['id'].$key['id'].$key['id'],'parentid'=>-1,'text'=>$key['name'],'value'=>'!'.$key['id'].'!');
        }
        $a = $this->db->where('id_level !=',0)->get('data_user')->result_array();
        foreach ($a as $key) {
            $data[] = array('id'=>$key['ID'],'parentid'=>"DIVLEV".$key['id_level'].$key['id_division'].$key['id_division'].$key['id_division'],'text'=>$key['name'],'value'=>'@'.$key['ID'].'@');
        }

        echo json_encode($data);
    }

    public function get_email_templates_json()
    {
        $data = $this->db->get('email_templates')->result_array();
        foreach ($data as $key) {
            echo "<option value='".$key['id']."'>".$key['judul']."</option>";
        }
    }

    public function solve_revert()
	{
        $this->general->logging();

		$data = $this->input->post();
		$this->db->where('id',$data['id']);
		$a = $this->db->get('data_all_error');
		$a = $a->row_array();
		if($a['updated_at']==NULL){
			$data['updated_at'] = date("Y-m-d H:i:s");
			$data['updated_by'] = $this->session->userdata('id');
			$this->db->where('id',$data['id']);
			$this->db->update('data_all_error',$data);
			echo "good";
		}else{
			echo "bad";
		}
	}


    public function send_email_solver_revert()
    {
        $data = $this->input->post();
        $this->db->where('id',$data['id']);
        $a = $this->db->get('data_all_error')->row_array();
        $this->db->where('id',$data['template']);
        $temp = $this->db->get('email_templates')->row_array();

        $kode_booking = $a['kode_booking'];
        $kasus = $a['kasus'];
        $template = $temp['template'];
        $subject = $temp['judul'];
        $tujuan = $temp['id_user'];

        $template = str_replace("{{kode_booking}}", $kode_booking, $template);
        $template = str_replace("{{name}}", $this->session->userdata('nama'), $template);

        $subject = str_replace("{{kode_booking}}", $kode_booking, $subject);

        $to = explode(",", $tujuan);
        $em = array();
        foreach ($to as $key) {
            $em[] = $this->general->get_email($key);
        }
        $em[] = $this->session->userdata('email');

        $this->general->kirim_email_solver_revert($em,$subject,$template);
        echo "sent";

    }
    public function get_last_activity($id)
    {
        $this->general->logging();
        $this->db->where('member_ID',$id);
        $this->db->where('delete_by',NULL);
        $this->db->order_by('id','desc');
        $this->db->limit(1);
        $a = $this->db->get('data_activity');
        $a = $a->row_array();
        $data = array();
        if(empty($a)){
            $data['followup'] = "No one follow up";
        }else{
            $data['followup'] = $a['type']." : ".$this->general->get_respon($a['id_respon'])." : ".$a['reason'];
        }

        $this->db->join('data_klasifikasi','data_klasifikasi.id=klasifikasi_member.id_klasifikasi','left');
        $this->db->where('id_mitra',$id);
        $this->db->order_by('klasifikasi_member.id','desc');
        $this->db->limit(1);
        $a = $this->db->get('klasifikasi_member');
        $a = $a->row_array();
        if(empty($a)){
            $data['klasifikasi'] = "No Data";
        }else{
            $data['klasifikasi'] = $a['klasifikasi'];
        }

        echo json_encode($data);

    }
    public function ajax_del_act()
    {
        $this->general->logging();
        $data = $this->input->post();
        $this->db->where('ID',$data['id']);
        $this->db->delete('data_activity');
    }
}
