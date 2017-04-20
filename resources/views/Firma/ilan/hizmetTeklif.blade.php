<div  id="hizmet">
    <?php $firma_id = session()->get('firma_id'); 
                    $kullanici_id=Auth::user()->kullanici_id;
              ?>
      
    {{ Form::open(array('id'=>'teklifForm','url'=>'teklifGonder/'.$firma_id .'/'.$ilan->id.'/'.$kullanici_id,'method' => 'post')) }}
    <table class="table">
        <thead id="tasks-list" name="tasks-list">
            <tr id="firma{{$firma->id}}">
                <?php
                if (!$firma->ilanlar)
                    $firma->ilanlar = new App\Ilan();
                if (!$firma->ilanlar->ilan_hizmetler)
                    $firma->ilanlar->ilan_hizmetler = new App\IlanHizmet();
                $i=1; 
                  $teklif= App\Teklif::where('firma_id',$firma_id)->where('ilan_id',$ilan->id)->get();
                ?>
            <tr>
                <th>Sıra:</th>
                <th>Adı:</th>
                <th>Fiyat Standartı:</th>
                <th>Fiyat Standartı Birimi:</th>
                <th>Miktar:</th>
                <th>Miktar Birimi:</th>
                <th>KDV Oranı:</th>
                <th>Birim Fiyat:</th>
                <th>Para Birimi</th>
                <th>Toplam:({{$firma->ilanlar->para_birimleri->adi}})</th>
            </tr>
            @foreach($ilan->ilan_hizmetler as $ilan_hizmet)
              <?php if(count($teklif) != 0){
                      $hizmetTeklif = App\HizmetTeklif::where('ilan_hizmet_id',$ilan_hizmet->id)->where('teklif_id',$teklif[0]['id'])->orderBy('id','DESC')->limit(1)->get();
                    } 
              ?>
            <tr>
                  <td>
                      {{$i++}}
                  </td>

                  <td>
                      {{$ilan_hizmet->adi}}
                  </td>
                  <td>
                      {{$ilan_hizmet->fiyat_standardi}}
                  </td>
                  <td>
                      {{$ilan_hizmet->fiyat_birimler->adi}}
                  </td>
                  <td>
                      {{$ilan_hizmet->miktar}}
                  </td>
                  <td>
                      {{$ilan_hizmet->miktar_birimler->adi}}
                  </td>
                  <td>
                    <select style="margin-top: 0px" class="form-control select kdv" name="kdv[]" id="kdv{{$i-2}}"  required>
                        <option value="-1" selected hidden>Seçiniz</option>
                        @if(count($teklif)!=0 && count($hizmetTeklif) != 0 && $hizmetTeklif[0]['kdv_orani'] == 0)
                             <option  value="0"  selected>%0</option>
                        @else
                             <option  value="0">%0</option>
                        @endif

                        @if(count($teklif)!=0 && count($hizmetTeklif) != 0 && $hizmetTeklif[0]['kdv_orani'] == 1)
                             <option  value="1" selected >%1</option>
                        @else
                             <option  value="1">%1</option>
                        @endif

                        @if(count($teklif)!=0 && count($hizmetTeklif) != 0 && $hizmetTeklif[0]['kdv_orani'] == 8)
                             <option  value="8" selected>%8</option>
                        @else
                             <option  value="8" >%8</option>
                        @endif

                        @if(count($teklif)!=0 && count($hizmetTeklif) != 0 && $hizmetTeklif[0]['kdv_orani'] == 18)    
                             <option  value="18" selected>%18</option>
                        @else
                             <option  value="18">%18</option>
                        @endif                                                              
                   </select>
                  </td>
                  <td>
                      @if($ilan->kismi_fiyat == 0)
                          @if(count($teklif)!=0 && count($hizmetTeklif) != 0)
                              <input style="margin-top: 0px" align="right" type="text" class="form-control fiyat kdvsizFiyat" name="birim_fiyat[]" placeholder="Fiyat" value="{{$hizmetTeklif[0]['kdv_haric_fiyat']}}" required>
                          @else
                              <input style="margin-top: 0px" align="right" type="text" class="form-control fiyat kdvsizFiyat" name="birim_fiyat[]" placeholder="Fiyat" value="0" required>
                          @endif
                      @else
                          @if(count($teklif)!=0 && count($hizmetTeklif) != 0)
                              <input style="margin-top: 0px" align="right" type="text" class="form-control fiyat kdvsizFiyat" name="birim_fiyat[]" placeholder="Fiyat" value="{{$hizmetTeklif[0]['kdv_haric_fiyat']}}">
                          @else
                              <input style="margin-top: 0px" align="right" type="text" class="form-control fiyat kdvsizFiyat" name="birim_fiyat[]" placeholder="Fiyat" value="0">
                          @endif
                      @endif  
                  </td>
                  <td></td><!-- Fiyat hesaplaması için gerekli -->
                  <td>
                      <span align="right" class="kalem_toplam" name="kalem_toplam" class="col-sm-3"></span>
                  </td>                                        
                  <input type="hidden" name="ilan_hizmet_id[]"  id="ilan_hizmet_id" value="{{$ilan_hizmet->id}}"> 
              </tr>
              @endforeach
              <tr>
                  <td colspan="8"></td>
                  <td colspan="3" style="text-align:right">
                      <label for="" id="toplamFiyatL" class="control-label toplam" ></label>
                      <input type="hidden" name="toplamFiyatKdvsiz"  id="toplamFiyatKdvsiz" value="">
                  </td>
                </tr>
              <tr>
                  <td colspan="8">
                            <input type="hidden" id="iskonto"><label id="iskontoLabel"></label>
                            <input style="width: 60px" type="hidden" name="iskontoVal" id="iskontoVal" value="" placeholder="yüzde">   
                  </td> 
                  <td colspan="3" style="text-align:right">
                      <label for="toplamFiyatLabel" id="toplamFiyatLabel" class="control-label toplam" ></label>
                      <input type="hidden" name="toplamFiyat"  id="toplamFiyat" value="">
                  </td>
              </tr>
              <tr>
                  <td colspan="5"></td>
                  <td colspan="3" style="text-align:right">
                      <label for="" id="iskontoluToplamFiyatL" class="control-label toplam" ></label>
                      <input type="hidden" name="iskontoluToplamFiyatKdvsiz"  id="iskontoluToplamFiyatKdvsiz" value="">
                  </td>
                  <td colspan="3" style="text-align:right">
                      <label for="" id="iskontoluToplamFiyatLabel" class="control-label toplam" ></label>
                      <input type="hidden" name="iskontoluToplamFiyatKdvli"  id="iskontoluToplamFiyatKdvli" value="">
                  </td>
              </tr>
          </tbody>
    </table>
    <div align="right">
         @if($ilan->kapanma_tarihi > $dt)
            @if(count($teklif)!= 0) <!--Teklif varsa buton güncelleme kontrolu -->
                {!! Form::submit('Teklif Güncelle', array('url'=>'teklifGonder/'.$firma_id.'/'.$ilan->id.'/'.$kullanici_id,'class'=>'btn btn-info')) !!}
            @else
                {!! Form::submit('Teklif Gönder', array('url'=>'teklifGonder/'.$firma_id.'/'.$ilan->id.'/'.$kullanici_id,'class'=>'btn btn-info')) !!}
            @endif
         @else
                    Bu ilanın KAPANMA SÜRESİ geçmiştir.O yüzden teklif günceleyemezsiniz !
         @endif
        {!! Form::close() !!}
    </div>       

</div>