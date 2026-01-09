<style>
    #isian_signature_ttd{
        width:fit-content;
        border:thin solid #ccc;
        line-height:0;
        background:white;
    }
</style>
<div align="center">
    <div id="isian_signature_ttd" class="mb-1" title="tanda tangan disini"> </div>
    <div style="width:300px;display:flex;justify-content: space-between;">
        <input type="file" id="file_ttdx" style="display:none" accept=".png">
        <button class="btn btn-sm btn-primary" onclick="$('#file_ttdx').trigger('click');" id="up_ttdx">Upload TTD</button>
        <button class="btn btn-sm btn-warning" id="clear_ttdx">Clear</button>
    </div>
</div>
<script> 
    var ttdx = $("#isian_signature_ttd")
    ttdx.jSignature({width:300,height:200}); 
    function TTDX(c,v=""){ return (v)?ttdx.jSignature(c,v):ttdx.jSignature(c); }
    $('#clear_ttdx').on('click',()=>TTDX('clear'));
    $('#file_ttdx').on('change',function(){
        if(this.files){
            var file = this.files[0];
            if(file.name.toLocaleLowerCase().includes('.png')){
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    console.log(reader.result);
                };
                reader.onerror = function (error) {
                    Swal.fire('Reading File Error','','error');
                };
            }else{
                Swal.fire('File tidak valid','File harus berupa gambar dengan format PNG','error');
            }
        }else{
            Swal.fire('No Image File','','error');
        }
    });
</script>