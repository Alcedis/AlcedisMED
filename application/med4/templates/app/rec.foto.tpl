{*
AlcedisMED
Copyright (C) 2010-2016  Alcedis GmbH

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*} 

<table class="formtable">

{html_set_header                    caption=#head_doku#     class="head"       }
{html_set_row     field="datum"     caption=$_datum_lbl     input=$_datum      }
{html_set_row     field="keywords"  caption=$_keywords_lbl  input=$_keywords   }

{if strlen($_foto_id_value)}
   {html_set_header    caption=#head_picture#     class="head"}
   {html_set_row     field="bez"     caption=$_bez_lbl     input=$_bez}
   <tr>
      <td class="lbl">
         {$_foto_lbl}
      </td><td class="edt">
         <a target='_blank' href='index.php?page=foto&amp;foto_id={$_foto_id_value}'><img class='thumb-img' alt='thumb' src='index.php?page=foto&amp;type=thumbnail&amp;foto_id={$_foto_id_value}'/></a>
         <input type='hidden' name='foto' value="{$foto}"/> {$_img_type}
      </td>
   </tr>
{else}
    {html_set_header                    caption=#head_pictures#     class="head"}
    <tr>
        <td colspan="2" style="padding:0px !important">
            <div class="info-msg" style="float:none !important">{#msg_upload#}</div>
        </td>
    </tr>
    <tr>
        <td class="lbl">{$_foto_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto'/>{$err_foto}<br/>
                    {$_bez_lbl} <br/>{$_bez}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez2_value) == 0 && strlen($err_foto2) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto2_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto2'/>{$err_foto2}<br/>
                    {$_bez2_lbl} <br/>{$_bez2}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez3_value) == 0 && strlen($err_foto3) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto3_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto3'/>{$err_foto3}<br/>
                    {$_bez3_lbl} <br/>{$_bez3}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez4_value) == 0 && strlen($err_foto4) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto4_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto4'/>{$err_foto4}<br/>
                    {$_bez4_lbl} <br/>{$_bez4}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez5_value) == 0 && strlen($err_foto5) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto5_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto5'/>{$err_foto5}<br/>
                    {$_bez5_lbl} <br/>{$_bez5}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez6_value) == 0 && strlen($err_foto6) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto6_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto6'/>{$err_foto6}<br/>
                    {$_bez6_lbl} <br/>{$_bez6}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez7_value) == 0 && strlen($err_foto7) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto7_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto7'/>{$err_foto7}<br/>
                    {$_bez7_lbl} <br/>{$_bez7}
                </div>
        </td>
    </tr>
    <tr {if strlen($_bez8_value) == 0 && strlen($err_foto8) == 0}class="img-selector-tr isHidden"{/if}>
        <td class="lbl">{$_foto8_lbl}</td>
        <td class="edt">
                <div class="img-selector">
                    <input type='file' size="50" name='foto8'/>{$err_foto8}<br/>
                    {$_bez8_lbl} <br/>{$_bez8}
                </div>
        </td>
    </tr>
    <tr class="img-add-tr">
        <td colspan="2" class="lbl">
            <div class="img-selector-add">
                <div style="float:left"><img src="media/img/base/add_plus.png" alt="" title="" /></div>
                <div style="float:left; padding-left:8px;padding-top:2px"><span>{#add#}</span></div>
            </div>
        </td>
    </tr>
{/if}

{html_set_header  field="bem"       caption=#head_bem#      class="head"       }
{html_set_header  field="bem"       caption=$_bem           class="edt"        }

</table>

{html_set_buttons modus=$button}

<div>
    {$_foto_id}
    {$_patient_id}
    {$_erkrankung_id}
</div>

{literal}<script type="text/javascript">$(function(){
  $('.img-selector-add').click(function(){
    var
        $next = $('.img-selector-tr.isHidden')
    ;

    if ($next.size() > 0) {
        $nextElement = $next.get(0);

        $($nextElement).show().removeClass('isHidden');

        if ($next.size() == 1) {
            $('.img-add-tr').hide();
        }
    }
  });

  var $currentHidden = $('.img-selector-tr.isHidden');

  if ($currentHidden.size() == 0) {
     $('.img-add-tr').hide();
  }

});</script>{/literal}