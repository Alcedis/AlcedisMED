/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */ 

$(function(){
   $(':input[name="btn_calculate"]').click(stampCalculate);
});

function stampCalculate()
{
    var minOneFilled = [0,0], //0 = rechts, 1 = links
        allStamps = [0,0], //0 = rechts, 1 = links
        positiveStamps = [0,0], //0 = rechts, 1 = links
        $fields = $(':input[name$="_beurteilung"]', 'table.stamps'),
        count = [],
        filled = false,
        val = null
    ;

    $fields.each(function(){
        var
            fieldName = $(this).attr('name').replace('_beurteilung', ''),
            side = (fieldName.substr(fieldName.length - 1) == 'r' ? 0 : 1),
            $extended = $(":input[name='" + fieldName + "_anz_positiv']")
        ;

        //count positive
        if ($extended.length) {
            var val = parseInt($(":input[name='" + fieldName + "_anz_positiv']").val());

            if (isNaN(val) === false) {
                minOneFilled[side] = 1;

                positiveStamps[side] = positiveStamps[side] + val;
            }

            for (var i = 1; i <= 5; i++) {
                filled = false;
                count  = [
                    $(":input[name='" + fieldName + '_' + i + '_laenge"]').val(),
                    $(":input[name='" + fieldName + '_' + i + '_tumoranteil"]').val(),
                    $(":input[name='" + fieldName + '_' + i + '_gleason1"]').val(),
                    $(":input[name='" + fieldName + '_' + i + '_gleason2"]').val()
                ];

                $(count).each(function(i, val){
                    if (val.length) {
                        filled = true;
                        return;
                    }
                });

                if (filled === true) {
                    minOneFilled[side] = 1;
                    allStamps[side] = allStamps[side] + 1;
                }
            }
        } else {
            val = $(this).val();

            if (val == 'p') {
                minOneFilled[side] = 1;
                positiveStamps[side] = positiveStamps[side] + 1;
                allStamps[side] = allStamps[side] + 1;
            } else {
                filled = false;
                count = [
                    fieldName + '_beurteilung',
                    fieldName + '_1_laenge',
                    fieldName + '_1_tumoranteil'
                ];

                $(count).each(function(i, field){
                    var $tmpField = $(":input[name='" + field + "']", 'table.stamps');

                    if ($tmpField.length && $tmpField.val().length) {
                        filled = true;
                        return;
                    }
                });

                if (filled === true) {
                    minOneFilled[side] = 1;
                    allStamps[side] = allStamps[side] + 1;
                }
            }
        }
    });

    $(minOneFilled).each(function(i, val){
        if (val == 1) {
            side = i == 0 ? 'r' : 'l';

            $(':input[name="' + side + '_anz"]').val(allStamps[i]);
            $(':input[name="' + side + '_anz_positiv"]').val(positiveStamps[i]);
        }
    });
}