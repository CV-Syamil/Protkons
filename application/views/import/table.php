<div class="card card-solid">
    <div class="card-header">
        <h3 class="card-title">Import Pelayanan</h3>
        <div class="card-tools">
            <button type="button" data-toggle="modal" data-target="#modal-import" class="btn bg-primary btn-tool"><i class="fa fa-upload"></i> Import</button>
            <button type="button" data-toggle="modal" data-target="#modal-template" class="btn bg-info btn-tool"><i class="fa fa-file-excel"></i> Template</button>
            <button type="button" data-toggle="modal" data-target="#modal-panduan" class="btn bg-warning btn-tool"><i class="fa fa-question-circle"></i> Panduan</button>
        </div>
    </div>
    <div class="card-body p-0" id="card-body-q" style="display:none;">
      <div class="progress"> <div id="progress-q" class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 40%"><b>40%</b></div> </div>
      <div  class="pt-2 pb-3 px-3" id="result-q"> </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-panduan">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Panduan Import Pelayanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Pelayanan</label>
          <select id="slc_pl2" class="form-control" required>
            <option value="">Pilih Pelayanan</option>
            <?php foreach($pl as $v){ echo "<option value=\"$v->pelayanan_id\">$v->kode_layanan - $v->pelayanan</option>"; } ?>
          </select>
        </div>
        <div id="panduan-body"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-import">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Pelayanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" id="form-import-x">
          <div class="form-group">
            <label>Pelayanan</label>
            <select id="slc_pl" class="form-control" required>
              <?php foreach($pl as $v){ echo "<option value=\"$v->pelayanan_id\">$v->kode_layanan - $v->pelayanan</option>"; } ?>
            </select>
          </div>
          <div class="form-group">
            <label>File Import</label>
            <input type="file" id="file_import" class="form-control" accept=".xls,.csv,.xlsx" required>
            <span class="text-info small">* Hanya boleh input file berformat XLSX, XLS, CSV. Mohon untuk menginput file excel sesuai dengan format yang telah disediakan.</span>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="form-import-x" class="btn btn-primary" >Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modal-template">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Template Import Pelayanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th width="100">Kode</th>
                <th>Pelayanan</th>
                <th width="50">Template</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($pl as $v){?>
                <tr>
                  <td align="center"><?=$v->kode_layanan?></td>
                  <td><?=$v->pelayanan?></td>
                  <td align="center">
                    <button type="button" data-name="Template-<?=url_title($v->kode_layanan.'-'.$v->pelayanan)?>" data-href="<?=site_url('import_pelayanan/template/'.$v->pelayanan_id)?>" class="btn btn-primary btn-generate-template"><i class="fa fa-file-excel"></i></button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div style="display:none">
    <table id="table-template" border="1">
      <thead></thead>
      <tbody></tbody>
    </table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
  $('.btn-generate-template').on('click',function(){
    var href = $(this).data('href');
    var name = $(this).data('name')??'Template';
    if(href){
      $.ajax({
        url: $(this).data('href'), type: 'GET', dataType: 'JSON',
        beforeSend:()=>$('.modal').block({message:'Generate Template...'}),
        error:(e)=>{$('.modal').unblock();toast('error',e.statusText,'Request Error '+e.status);},
        success:(r)=>{
          if(r.fields){
            var td="";
            r.fields.forEach((d,i)=>{
              td+=`<td>${d}</td>`;
            });
            $('#table-template thead').html(`<tr>${td}</tr>`);
            $('.modal').unblock();
            var data = document.getElementById('table-template');
            var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
            XLSX.write(excelFile, { bookType: 'xls', bookSST: true, type: 'base64' });
            XLSX.writeFile(excelFile, name+'.xls');
          }else{
            toast('error','Generate Template Error','Response Error');
          }
        }
      });
    }else{
      toast('error','Gagal Generate Template');
    }
  });
  var dataJs=[];
  var ExcelToJSON = function() {
    this.parseExcel = function(file) {
      var reader = new FileReader();

      reader.onload = function(e) {
        var data = e.target.result;
        var workbook = XLSX.read(data, {
          type: 'binary'
        });
        workbook.SheetNames.forEach(function(sheetName) {
          // Here is your object
          var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
          dataJs=XL_row_object;
          prosesImport();
        })
      };

      reader.onerror = function(ex) {
        toast('error','Reading File Excel Errors');
      };

      reader.readAsBinaryString(file);
    };
  };
  var qdt=[];
  var perpage = 10;
  var cpage = 0;
  function prosesImport(){
    if(dataJs.length>0){
      qdt = [];
      tdt=[];
      cpage=0;
      dataJs.forEach((d,i) => {
        tdt.push(d);
        if((i>0&&(i%perpage)==0)||i==(dataJs.length-1)){
          qdt.push(tdt);
          tdt=[];
        }
      });
      $('#result-q').html('');
      $('#card-body-q').show();
      $('.modal').modal('hide');
      requestData();
    }else{
      $('#card-body-q').hide();
      toast('error','Data Excel Kosong');
    }
  }

  function requestData(){
    if(cpage<qdt.length){
      $.ajax({
        url: '<?=site_url("import_pelayanan")?>', type: 'POST', 
        data:{pl:$('#slc_pl').val(),data:qdt[cpage]}, dataType:'JSON',
        noerror: true,
        beforeSend:()=>updateProgress(),
        complete:(p1,p2)=>{
          if(p2=='success'&&p1.responseJSON){
            var jsr = p1.responseJSON;
            if(jsr.status!=200){
              resError(jsr.message,cpage);
            }
          }else{
            resError(p2.toUpperCase(),cpage);
          }
          cpage++;
          setTimeout(()=>requestData(), 500);  
        }
      });
    }else{
      $('#progress-q').parent().hide();
      toast('success','Import Complete...');
    }
  }
  function resError(msg,page){
    var tbody="";
    qdt[page].forEach(d=>{
      tbody+=`
        <tr>
          <td align="center"><i>${d.__rowNum__+1}</i></td>
          <td align="center">${d.no_dokumen??'-'}</td>
          <td>${d.tgl_dokumen??'-'}</td>
          <td>${d.pelapor_no_identitas??'-'}</td>
          <td>${d.pelapor_nama??'-'}</td>
        </tr>
      `;
    });
    var str = `<div class="card card-danger">
          <div class="card-header"><h4 class="card-title"><strong>Error</strong> : ${msg}</h4></div>
          <div class="card-body pt-1">
            <label>Data</label>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th width="15" class="pl-0">Row</th>
                    <th>No. Doc.</th>
                    <th>Tgl. Doc.</th>
                    <th>No. Identitas</th>
                    <th>Nama Pelapor</th>
                  </tr>
                </thead>
                <tbody>${tbody}</tbody>
              </table>
            </div>
          </div>
        </div>`;
    $('#result-q').prepend(str);
  }
  function updateProgress(){
      var x = (cpage==0)?5:(cpage/qdt.length*100).toFixed();
      $('#progress-q').parent().show();
      $('#progress-q').css('width',`${x}%`);
      $('#progress-q').html(`${x}%`);
  }

  var slc_pl = "";
  $('#form-import-x').on('submit', function(e){
    e.preventDefault();
    var files = $('#file_import')[0].files;
    if(files.length>0){
      var file = files[0];
      var name_split = file.name.split('.');
      var ext = name_split[name_split.length-1];
      if(['xls','xlsx','csv'].includes(ext.trim().toLocaleLowerCase())){
        var xlsjs = new ExcelToJSON();
        slc_pl = $('#slc_pl').val();
        xlsjs.parseExcel(file);
      }else{
        toast('error','Mohon pilih file excel (XLS,XLSX,CSV)','Format file tidak disetujui.');
      }
    }else{
      toast('error','Mohon pilih file excel');
    }
  });
  $('#slc_pl2').on('change', function(){
    var v = this.value;
    if(v){
      $.ajax({
        url: '<?=site_url('import_pelayanan/panduan')?>', type:'POST', data:{pl:v},
        beforeSend:()=>blockUI('#modal-panduan .modal-dialog'),
        complete:()=>blockUI('#modal-panduan .modal-dialog',false),
        success:(r)=>{ $('#panduan-body').html(r); }
      });
    }else{
      $('#panduan-body').html('');
    }
  });
</script>