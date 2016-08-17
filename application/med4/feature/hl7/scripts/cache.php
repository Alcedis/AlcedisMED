<?php

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

if (appSettings::get('active', 'hl7') !== true) {
   exit;
}

$error               = false;
$cacheMessageFiles   = array();

// process time
$timestamp           = date('Y-m-d H:i:s');
$orgId               = isset($_REQUEST['org_id']) === true ? (int) $_REQUEST['org_id'] : false;

$hl7Main             = hl7Main::getInstance();

$cacheDir            = $hl7Main->getSettings('cache_dir') . ($orgId !== false ? "{$orgId}/": null);

$hl7Main
    ->setProcessHash(uniqid())
    ->refresh('log_cache.logtime', 'log', 'log')
    ->refresh('cache.createtime', 'usability', 'cache')
;

//Cache Ordner prüfen
if (is_dir($cacheDir) === false) {
   echo '<!-- cache dir not found -->';
   $error = true;
} else {
   $openCacheDir = opendir($cacheDir);

   if ($openCacheDir !== false) {

      //Wenn spezielle Org Id
      if ($orgId !== false) {
         while ($curFileName = readdir($openCacheDir)) {
            if (in_array($curFileName, array('.', '..')) == false && is_file($cacheDir . $curFileName) === true) {
               $cacheMessageFiles[$orgId][] = $curFileName;
            }
         }
      } else {
         while ($currentOrgId = readdir($openCacheDir)) {
            if (in_array($currentOrgId, array('.', '..')) == false && is_dir($cacheDir . $currentOrgId) === true) {

               $orgDir = opendir($cacheDir . $currentOrgId);

               while ($curFileName = readdir($orgDir)) {
                  if (in_array($curFileName, array('.', '..')) == false && is_file($cacheDir . $currentOrgId  . '/' . $curFileName) === true) {
                     $cacheMessageFiles[$currentOrgId][] = $curFileName;
                  }
               }
               closedir( $orgDir );
            }
         }
      }
      closedir( $openCacheDir );
  } else {
     echo '<!-- cache dir not accessable -->';
     $error = true;
  }
}

//Wenn ein Fehler bei dem Laden der Message Files auftritt
if ($error === true || count($cacheMessageFiles) == 0) {
    if ($error === false) {
        echo '<!-- no messages found to import -->';
    }
   exit;
}

$usedCacheIds = array();

//Caching
foreach ($cacheMessageFiles as $cacheFolderOrgId => $messageFiles) {
    foreach ($messageFiles as $fileName) {
        $filePath = $orgId !== false
            ? $cacheDir . $fileName
            : $cacheDir . $cacheFolderOrgId . '/' . $fileName
        ;

        $hl7Main->reset();

        $messages = $hl7Main->importFile($filePath, true);

        //Message ist leer
        if ($messages !== null) {
            // check all messages in file
            foreach ($messages as $i => $message) {
                $hl7Main
                   ->reset('writer')
                   ->reset('message')
                   ->reset('log')
                   ->setLogType('cache')
                   ->loadMsg($message['con'])
                ;

                $messageType   = implode('/', array($hl7Main->getFieldValue('messageType'), $hl7Main->getFieldValue('messageId')));
                $rawMessage    = $message['raw'];

                $nachname      = $hl7Main->getFieldValue('patient.nachname');
                $vorname       = $hl7Main->getFieldValue('patient.vorname');
                $bday          = $hl7Main->getFieldValue('patient.geburtsdatum');

                $patientNr     = $hl7Main->getFieldValue('patient.patient_nr');
                $aufnahmeNr    = $hl7Main->getFieldValue('aufenthalt.aufnahmenr');

                $hl7Main->setLogData(array(
                    'org_id'       => $cacheFolderOrgId,
                    'logtime'      => $timestamp,
                    'vorname'      => $vorname,
                    'nachname'     => $nachname,
                    'geburtsdatum' => $bday,
                    'patient_nr'   => $patientNr,
                    'aufnahme_nr'  => $aufnahmeNr,
                    'msg'          => $rawMessage
                ));

                $validEvent    = (str_contains($hl7Main->getSettings('valid_event_types'), $messageType) === true);
                $hasPIDSegment = ($hl7Main->hasSegment('PID') === true);

                if ($validEvent === true && $hasPIDSegment === true) {
                    $disease        = null;
                    $diagnose       = null;
                    $diagnoseSub    = null;

                    $hl7Main->check('pre', 'division');
                    $preCacheDiagnose = $hl7Main->check('pre', 'Diagnose', $hl7Main->check('pre', 'DiagnoseType'));

                    if ($hl7Main->skipMessage() === false) {
                        if ($preCacheDiagnose !== false) {
                            $diagnose = $preCacheDiagnose !== null ? reset(explode(' ', trim($preCacheDiagnose))) : null;

                            $disease = $hl7Main->getLookups('diagnose', $diagnose, $hl7Main->getSettings('diseaseRestriction'));

                            if ($disease === null) {
                                $diagnoseSub = substr($diagnose, 0, 3);
                                $disease  = $hl7Main->getLookups('diagnose', $diagnoseSub, $hl7Main->getSettings('diseaseRestriction'));
                            }
                        } else {
                            foreach (explode($hl7Main->getDelimiter(), $hl7Main->getFieldValue('diagnoseCode')) as $cacheValue) {
                                $diagnose = $cacheValue !== null ? reset(explode(' ', trim($cacheValue))) : null;
                                $disease  = $hl7Main->getLookups('diagnose', $diagnose, $hl7Main->getSettings('diseaseRestriction'));

                                if ($disease === null) {
                                    $diagnoseSub  = substr($diagnose, 0, 3);
                                    $disease      = $hl7Main->getLookups('diagnose', $diagnoseSub, $hl7Main->getSettings('diseaseRestriction'));
                                }

                                if ($disease !== null) {
                                    break;
                                }
                            }
                        }

                        $hl7Main
                            ->addLogFilter('message', 'valid_message')
                            ->addLogFilter('diagnose', ($diagnoseSub !== null ? $diagnoseSub : $diagnose))
                            ->addLogFilter('disease', $disease)
                            ->setLogStatus('valid')
                        ;
                    }

                    $hl7Main
                        ->setLogData(array('erkrankung' => $disease))
                        ->writeLog()
                    ;

                    if ($hl7Main->skipMessage() === true) {
                        continue;
                    }

                    $messageData = array(
                        'patient' => array(
                            'hl7_cache_id' => null,
                            'org_id'       => $cacheFolderOrgId,
                            'createtime'   => $timestamp,
                            'vorname'      => $vorname,
                            'nachname'     => $nachname,
                            'geburtsdatum' => $bday,
                            'patient_nr'   => $patientNr,
                            'aufnahme_nr'  => $aufnahmeNr,
                            'erkrankung'   => $disease,
                        ),
                        'message' => array(
                            'hl7_cache_id'       => null,
                            'createtime'         => $timestamp,
                            'message_control_id' => $hl7Main->getMessageControlId(),
                            'message'            => $rawMessage
                        )
                    );

                    $cacheId = $hl7Main->writeToCache($messageData);

                    $usedCacheIds[$cacheId] = true;
                } else {
                    // invalid Message Type or no PID segment
                    $hl7Main
                        ->addLogFilter('message', 'invalid_message')
                        ->addLogFilter('type', 'message_type_or_pid')
                        ->addLogFilter('invalidType', ($validEvent === false ? "{$messageType}" : 'false'))
                        ->addLogFilter('noPid', ($hasPIDSegment === false ? 'true' : 'false'))
                        ->setLogStatus('error')
                        ->writeLog()
                    ;
                }
            }
        }

        unlink($filePath);
    }
}

// update patient due cache
if ($hl7Main->getSettings('update_patient_due_caching') == 1) {
    $hl7Main
        ->processCache(array_keys($usedCacheIds), true)
    ;
}

if ($hl7Main->getSettings('import_mode') == 'auto') {
    $hl7Main->startImport();
}

?>
