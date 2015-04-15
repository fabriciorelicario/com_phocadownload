<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class PhocaDownloadHelper
{	
	function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId) {		

		foreach ($data as $key) {	
			$show_text =  $text . $key->text;
			
			if ($key->parentid == $id && $currentId != $id && $currentId != $key->value) {
				$tree[$key->value] 			= new JObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = PhocaDownloadHelper::CategoryTreeOption($data, $tree, $key->value, $show_text . " - ", $currentId );	
			}	
		}
		return($tree);
	}

	
	/**
	 * Method to display multiple select box
	 * @param string $name Name (id, name parameters)
	 * @param array $active Array of items which will be selected
	 * @param int $nouser Select no user
	 * @param string $javascript Add javascript to the select box
	 * @param string $order Ordering of items
	 * @param int $reg Only registered users
	 * @return array of id
	 */
	
	function usersList( $name, $id, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1 ) {
		
		$activeArray = $active;
		if ($active != '') {
			$activeArray = explode(',',$active);
		}
		
		$db		= &JFactory::getDBO();
		$and 	= '';
		if ($reg) {
			// does not include registered users in the list
			$and = ' AND m.group_id != 2';
		}

		$query = 'SELECT u.id AS value, u.name AS text'
		. ' FROM #__users AS u'
		. ' JOIN #__user_usergroup_map AS m ON m.user_id = u.id'
		. ' WHERE u.block = 0'
		. $and
		. ' GROUP BY u.id'
		. ' ORDER BY '. $order;
		
		
		
		$db->setQuery( $query );
		if ( $nouser ) {
			
			// Access rights (Default open for all)
			// Upload and Delete rights (Default closed for all)
			switch ($name) {
				case 'jform[accessuserid][]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'COM_PHOCADOWNLOAD_ALL_REGISTERED_USERS' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'COM_PHOCADOWNLOAD_NOBODY' );
				break;
				
				Default:
					$idInput1 	= -2;
					$idText1	= JText::_( 'COM_PHOCADOWNLOAD_NOBODY' );
					$idInput2 	= -1;
					$idText2	= JText::_( 'COM_PHOCADOWNLOAD_ALL_REGISTERED_USERS' );
				break;
			}
			
			$users[] = JHTML::_('select.option',  $idInput1, '- '. $idText1 .' -' );
			$users[] = JHTML::_('select.option',  $idInput2, '- '. $idText2 .' -' );
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}

		$users = JHTML::_('select.genericlist', $users, $name, 'class="inputbox" size="4" multiple="multiple"'. $javascript, 'value', 'text', $activeArray, $id );

		return $users;
	}
	
	
	
	
	
	
	function getAliasName($name) {
		
		$paramsC		= &JComponentHelper::getParams( 'com_phocadownload' );
		$alias_iconv	= $paramsC->get( 'alias_iconv', 0 );
		
		$iconv = 0;
		if ($alias_iconv == 1) {
			if (function_exists('iconv')) {
				$name = preg_replace('~[^\\pL0-9_.]+~u', '-', $name);
				$name = trim($name, "-");
				$name = iconv("utf-8", "us-ascii//TRANSLIT", $name);
				$name = strtolower($name);
				$name = preg_replace('~[^-a-z0-9_.]+~', '', $name);
				$iconv = 1;
			} else {
				$iconv = 0;
			}
		}
		
		if ($iconv == 0) {
			$name = JFilterOutput::stringURLSafe($name);
		}
		
		if(trim(str_replace('-','',$name)) == '') {
			$datenow	= &JFactory::getDate();
			$name 		= $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}
		return $name;
	}
	
	
	
	
	
	
	function filterCategory($query, $active = NULL, $frontend = NULL, $onChange = TRUE, $fullTree = NULL ) {
		$db	= & JFactory::getDBO();
		
		

		$form = 'adminForm';
		if ($frontend == 1) {
			$form = 'phocadownloadfilesform';
		}
		
		if ($onChange) {
			$onChO = 'class="inputbox" size="1" onchange="document.'.$form.'.submit( );"';
		} else {
			$onChO = 'class="inputbox" size="1"';
		}
		
		$categories[] = JHTML::_('select.option', '0', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_CATEGORY').' -');
		$db->setQuery($query);
		$catData = $db->loadObjectList();
		
		
		
		if ($fullTree) {
			
			// Start - remove in case there is a memory problem
			$tree = array();
			$text = '';
			
			$queryAll = ' SELECT cc.id AS value, cc.title AS text, cc.parent_id as parentid'
					.' FROM #__phocadownload_categories AS cc'
					.' ORDER BY cc.ordering';
			$db->setQuery($queryAll);
			$catDataAll 		= $db->loadObjectList();

			$catDataTree	= PhocaDownloadHelper::CategoryTreeOption($catDataAll, $tree, 0, $text, -1);
			
			$catDataTreeRights = array();
			/*foreach ($catData as $k => $v) {
				foreach ($catDataTree as $k2 => $v2) {
					if ($v->value == $v2->value) {
						$catDataTreeRights[$k]->text 	= $v2->text;
						$catDataTreeRights[$k]->value = $v2->value;
					}
				}
			}*/
			
			foreach ($catDataTree as $k => $v) {
                foreach ($catData as $k2 => $v2) {
                   if ($v->value == $v2->value) {
                      $catDataTreeRights[$k] = new StdClass();
					  $catDataTreeRights[$k]->text  = $v->text;
                      $catDataTreeRights[$k]->value = $v->value;
                   }
                }
             }

			
			
			$catDataTree = array();
			$catDataTree = $catDataTreeRights;
			// End - remove in case there is a memory problem
			
			// Uncomment in case there is a memory problem
			//$catDataTree	= $catData;
		} else {
			$catDataTree	= $catData;
		}	
	
		$categories = array_merge($categories, $catDataTree );

		$category = JHTML::_('select.genericlist',  $categories, 'catid', $onChO, 'value', 'text', $active);

		return $category;
	}
	/*
	function filterSection($query, $active = NULL, $frontend = NULL) {
		$db	= & JFactory::getDBO();
		$form = 'adminForm';
		if ($frontend == 1) {
			$form = 'phocadownloadfilesform';
		}
	
		$sections[] = JHTML::_('select.option', '0', '- '.JText::_('PHOCADOWNLOAD_SELECT_SECTION').' -');
		$db->setQuery( $query );
		$sections = array_merge($sections, $db->loadObjectList());

		$section = JHTML::_( 'select.genericlist', $sections, 'filter_sectionid',  'class="inputbox" size="1" onchange="document.'.$form.'.submit( );"' , 'value', 'text', $active );
		
		return $section;
	}
	*/
	function strTrimAll($input) {
		$output	= '';
	    $input	= trim($input);
	    for($i=0;$i<strlen($input);$i++) {
	        if(substr($input, $i, 1) != " ") {
	            $output .= trim(substr($input, $i, 1));
	        } else {
	            $output .= " ";
	        }
	    }
	    return $output;
	}
	
	function resetHits($redirect, $id)
	{
		$app = JFactory::getApplication();

		// Initialize variables
		$db	= & JFactory::getDBO();

		// Instantiate and load an article table
		$row = & JTable::getInstance('content');
		$row->Load($id);
		$row->hits = 0;
		$row->store();
		$row->checkin();

		$msg = JText::_('Successfully Reset Hit count');
		$app->redirect('index.php?option=com_content&sectionid='.$redirect.'&task=edit&id='.$id, $msg);
	}
	
	function getTitleFromFilenameWithoutExt (&$filename) {
	
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		$string = false;
		$string = preg_match( "/\./i", $folder_array[$last_array_value] );
		if ($string) {
			return PhocaDownloadHelper::removeExtension($folder_array[$last_array_value]);
		} else {
			return $folder_array[$last_array_value];
		}
	}
	
	function removeExtension($file_name) {
		return substr($file_name, 0, strrpos( $file_name, '.' ));
	}
	
	function getExtension( $file_name ) {
		return strtolower( substr( strrchr( $file_name, "." ), 1 ) );
	}
	
	
	function canPlay( $fileName ) {
		$fileExt 	= PhocaDownloadHelper::getExtension($fileName);
		
		switch($fileExt) {
			case 'mp3':
			case 'mp4':
			case 'flv':
			//case 'mov':
			//case 'wmv':
				return true;
			break;
			
			default:
				return false;
			break;
		
		}
	
		return false;
	}
	
	function getManagerGroup($manager) {
		
		$group = array();
		switch ($manager) {
			case 'icon':
			case 'iconspec1':
			case 'iconspec2':
				$group['f'] = 2;//File
				$group['i'] = 1;//Image
				$group['t'] = 'icon';//Text
				$group['c']	= '&amp;tmpl=component';
			break;
			
			
			case 'image':
				$group['f'] = 2;//File
				$group['i'] = 1;//Image
				$group['t'] = 'image';//Text
				$group['c']	= '&amp;tmpl=component';
			break;
			
			case 'filepreview':
				$group['f'] = 3;
				$group['i'] = 1;
				$group['t'] = 'filename';
				$group['c']	= '&amp;tmpl=component';
			break;
			
			case 'fileplay':
				$group['f'] = 3;
				$group['i'] = 0;
				$group['t'] = 'filename';
				$group['c']	= '&amp;tmpl=component';
			break;
			
			case 'filemultiple':
				$group['f'] = 1;
				$group['i'] = 0;
				$group['t'] = 'filename';
				$group['c']	= '';
			break;
			
			case 'file':
			default:
				$group['f'] = 1;
				$group['i'] = 0;
				$group['t'] = 'filename';
				$group['c']	= '&amp;tmpl=component';
			break;
		}
		return $group;
	}
	
	 
	function getPathSet( $manager = '') {
	
		$group = PhocaDownloadHelper::getManagerGroup($manager);
		
		// Params
		$paramsC			= &JComponentHelper::getParams( 'com_phocadownload' );
		// Folder where to stored files for download
		$downloadFolder		= $paramsC->get( 'download_folder', 'phocadownload' );
		$downloadFolderPap	= $paramsC->get( 'download_folder_pap', 'phocadownloadpap' );
		// Absolute path which can be outside public_html - if this will be set, download folder will be ignored
		$absolutePath		= $paramsC->get( 'absolute_path', '' );
		
		// Path of preview and play
		$downloadFolderPap 			= JPath::clean($downloadFolderPap);
		$path['orig_abs_pap'] 		= JPATH_ROOT .  DS . $downloadFolderPap;
		$path['orig_abs_pap_ds'] 	= $path['orig_abs_pap'] . DS ;
	
		if ($group['f'] == 2) {
			// Images
			$path['orig_abs'] 				= JPATH_ROOT . DS . 'images' . DS . 'phocadownload' ;
			$path['orig_abs_ds'] 			= $path['orig_abs'] . DS ;
			$path['orig_abs_user_upload'] 	= '';//$path['orig_abs'] . DS . 'userupload' ;
			$path['orig_rel_ds'] 			= '../images/phocadownload/';
		} else if ($group['f'] == 3) {
			// Play and Preview
			$path['orig_abs'] 				= $path['orig_abs_pap'];
			$path['orig_abs_ds'] 			= $path['orig_abs_pap_ds'];
			$path['orig_abs_user_upload'] 	= '';//$path['orig_abs'] . DS . 'userupload' ;
			$path['orig_rel_ds'] 			= '../'.str_replace('/', DS, JPath::clean($downloadFolderPap)).'/';
		} else {
			// Standard Path	
			if ($absolutePath != '') {
				$downloadFolder 				= str_replace('/', DS, JPath::clean($absolutePath));
				$path['orig_abs'] 				= str_replace('/', DS, JPath::clean($absolutePath));
				$path['orig_abs_ds'] 			= JPath::clean($path['orig_abs'] . DS) ;
				$path['orig_abs_user_upload'] 	= JPath::clean($path['orig_abs'] . DS . 'userupload') ;
				//$downloadFolderRel 	= str_replace(DS, '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 			= '';
				
			} else {
				$downloadFolder 				= str_replace('/', DS, JPath::clean($downloadFolder));
				$path['orig_abs'] 				= JPATH_ROOT . DS . $downloadFolder ;
				$path['orig_abs_ds'] 			= JPATH_ROOT . DS . $downloadFolder . DS ;
				$path['orig_abs_user_upload'] 	= $path['orig_abs'] . DS . 'userupload' ;
				
				$downloadFolderRel 				= str_replace(DS, '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 			= '../' . $downloadFolderRel .'/';
			}
		}
		return $path;
	}
	
	function getPhocaVersion()
	{
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.'com_phocadownload';
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.'com_phocadownload';
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	
	function getFileSize($filename, $readable = 1) {
		
		$path			= &PhocaDownloadHelper::getPathSet();
		$fileNameAbs	= JPath::clean($path['orig_abs'] . DS . $filename);
		
		if ($readable == 1) {
			return PhocaDownloadHelper::getFileSizeReadable(filesize($fileNameAbs));
		} else {
			return filesize($fileNameAbs);
		}
	}
	
	
	
	/*
	 * http://aidanlister.com/repos/v/function.size_readable.php
	 */
	function getFileSizeReadable ($size, $retstring = null, $onlyMB = false) {
	
		if ($onlyMB) {
			$sizes = array('B', 'kB', 'MB');
		} else {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }
		

		if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);
		
        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
		
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}
	
	
	function getFileTime($filename, $function, $format = "d. M Y") {
		
		$path			= &PhocaDownloadHelper::getPathSet();
		$fileNameAbs	= JPath::clean($path['orig_abs'] . DS . $filename);
		if (JFile::exists($fileNameAbs)) {
			switch($function) {
				case 2:
					$fileTime = filectime($fileNameAbs);
				break;
				case 3:
					$fileTime = fileatime($fileNameAbs);
				break;
				case 1:
				default:
					$fileTime = filemtime($fileNameAbs);
				break;
			}
			
			$fileTime = JHTML::Date($fileTime, $format);
		} else {
			$fileTime = '';
		}
		return $fileTime;
	}


	
	function getTitleFromFilenameWithExt (&$filename) {
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return $folder_array[$last_array_value];
	}

	
	function getMimeType($extension, $params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$returnMime = '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}

			// Return mime if extension call it
			if ($extension == $values[0]) {
				$returnMime = $values[1];
			}
		}

		if ($returnMime != '') {
			return $returnMime;
		} else {
			return "PhocaErrorNoMimeFound";
		}
	}
	
	function getMimeTypeString($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$extString 	= '';
		$mimeString	= '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
				
			// Create strings
			$extString .= $values[0];
			$mimeString .= $values[1];
			
			$j = $i + 1;
			if ($j < $count_matches) {
				$extString .=',';
				$mimeString .=',';
			}
		}
		
		$string 		= array();
		$string['mime']	= $mimeString;
		$string['ext']	= $extString;
		
		return $string;
	}
	
	
	function getDefaultAllowedMimeTypesDownload() {
		return '{hqx=application/mac-binhex40}{cpt=application/mac-compactpro}{csv=text/x-comma-separated-values}{bin=application/macbinary}{dms=application/octet-stream}{lha=application/octet-stream}{lzh=application/octet-stream}{exe=application/octet-stream}{class=application/octet-stream}{psd=application/x-photoshop}{so=application/octet-stream}{sea=application/octet-stream}{dll=application/octet-stream}{oda=application/oda}{pdf=application/pdf}{ai=application/postscript}{eps=application/postscript}{ps=application/postscript}{smi=application/smil}{smil=application/smil}{mif=application/vnd.mif}{xls=application/vnd.ms-excel}{ppt=application/powerpoint}{wbxml=application/wbxml}{wmlc=application/wmlc}{dcr=application/x-director}{dir=application/x-director}{dxr=application/x-director}{dvi=application/x-dvi}{gtar=application/x-gtar}{gz=application/x-gzip}{php=application/x-httpd-php}{php4=application/x-httpd-php}{php3=application/x-httpd-php}{phtml=application/x-httpd-php}{phps=application/x-httpd-php-source}{js=application/x-javascript}{swf=application/x-shockwave-flash}{sit=application/x-stuffit}{tar=application/x-tar}{tgz=application/x-tar}{xhtml=application/xhtml+xml}{xht=application/xhtml+xml}{zip=application/x-zip}{mid=audio/midi}{midi=audio/midi}{mpga=audio/mpeg}{mp2=audio/mpeg}{mp3=audio/mpeg}{aif=audio/x-aiff}{aiff=audio/x-aiff}{aifc=audio/x-aiff}{ram=audio/x-pn-realaudio}{rm=audio/x-pn-realaudio}{rpm=audio/x-pn-realaudio-plugin}{ra=audio/x-realaudio}{rv=video/vnd.rn-realvideo}{wav=audio/x-wav}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{css=text/css}{html=text/html}{htm=text/html}{shtml=text/html}{txt=text/plain}{text=text/plain}{log=text/plain}{rtx=text/richtext}{rtf=text/rtf}{xml=text/xml}{xsl=text/xml}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{movie=video/x-sgi-movie}{doc=application/msword}{xl=application/excel}{eml=message/rfc822}{pptx=application/vnd.openxmlformats-officedocument.presentationml.presentation}{xlsx=application/vnd.openxmlformats-officedocument.spreadsheetml.sheet}{docx=application/vnd.openxmlformats-officedocument.wordprocessingml.document}{rar=application/x-rar-compressed}{odf=application/x-vnd.oasis.opendocument.formula}';
	}
	
	function getDefaultAllowedMimeTypesUpload() {
		return '{pdf=application/pdf}{ppt=application/powerpoint}{gz=application/x-gzip}{tar=application/x-tar}{tgz=application/x-tar}{zip=application/x-zip}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{txt=text/plain}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{doc=application/msword}';
	}
	
	function getHTMLTagsUpload() {
		return array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
	}
	
	
	/*
	function getSettings($title = '', $default = '') {
	
		$db		=& JFactory::getDBO();
		$wheres = array();
		
		if ($title == '') {
			$select	= 'st.*';
			$where	= '';
		} else {
			$select		= 'st.value';
			$wheres[]	= 'st.title =\''.$title.'\'';
			
			$where = " WHERE " . implode( " AND ", $wheres );
		}
		
		
		$query = ' SELECT '.$select
			. ' FROM #__phocadownload_settings AS st'
			. $where
			. ' ORDER BY st.id';
			
		$db->setQuery($query);
		$settings = $db->loadObjectList();
		
		
		// All ITEMS
		if ($title == '') {
			return $settings;
		} else {
			// ONLY ONE ITEM
			if (empty($settings)) {
				return $default;
			} else {
				if (isset($settings[0]->value)) {
					return($settings[0]->value);
				} else {
					return '';
				}
			}
		}
	}
	
	function getSettingsValues($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$values = array();
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$phocaDownload	= $matches[0][$i][0];
			preg_match($regex_one,$phocaDownload,$phocaDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $phocaDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
			
			// Create strings
			$returnValues[$i]['id']	= $values[0];
			$returnValues[$i]['value']	= $values[1];
		}

		return $returnValues;
	}
	
	
	function getTextareaSettings($id, $title, $value, $class = 'text_area', $rows = 8, $cols = 50, $style = 'width:300px' ) {
		
		return '<textarea class="'.$class.'" name="phocaset['.$id.']" id="phocaset['.$id.']" rows="'.$rows.'" cols="'.$cols.'" style="'.$style.'" title="'.JText::_( $title . ' DESC' ).'" />'.$value.'</textarea>';
	}
	
	function getTextareaEditorSettings($id, $title, $value, $class = 'text_area', $rows = 20, $cols = 60, $width = 750, $height = 300 ) {
		
		//return '<textarea class="'.$class.'" name="phocaset['.$id.']" id="phocaset['.$id.']" rows="'.$rows.'" cols="'.$cols.'" style="'.$style.'" title="'.JText::_( $title . ' DESC' ).'" />'.$value.'</textarea>';
		$editor =& JFactory::getEditor();
		return $editor->display( 'phocaset['.$id.']',  $value, $width, $height, $cols, $rows, array('pagebreak', 'readmore') ) ;
	}
	
	function getTextSettings($id, $title, $value, $class = 'text_area', $size = 50, $maxlength = 255, $style = 'width:300px' ) {
		
		return '<input class="'.$class.'" type="text" name="phocaset['.$id.']" id="phocaset['.$id.']" value="'.$value.'" size="'.$size.'" maxlength="'.$maxlength.'" style="'.$style.'" title="'.JText::_( $title . ' DESC' ).'" />';
	}
	
	function getSelectSettings($id, $title, $value, $values, $class = 'inputbox', $size = 50, $maxlength = 255, $style = 'width:300px' ) {
		
		$valuesArray = PhocaDownloadHelper::getSettingsValues($values);
		
		$select = '<select name="phocaset['.$id.']" id="phocaset['.$id.']" class="'.$class.'">'. "\n";
		foreach ($valuesArray as $valueOption) {
			if ($value == $valueOption['id']) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			
			$select .= '<option value="'.$valueOption['id'].'" '.$selected.'>'.JText::_($valueOption['value']).'</option>' . "\n";
		}
		$select .= '</select>'. "\n";

		return $select;					
	}
	*/
	function displayNewIcon ($date, $time = 0) {
		
		if ($time == 0) {
			return '';
		}
		
		$dateAdded 	= strtotime($date, time());
		$dateToday 	= time();
		$dateExists = $dateToday - $dateAdded;
		$dateNew	= $time * 24 * 60 * 60;
		
		if ($dateExists < $dateNew) {
			return '&nbsp;'. JHTML::_('image', 'components/com_phocadownload/assets/images/icon-new.png', JText::_('COM_PHOCADOWNLOAD_NEW'));
		} else {
			return '';
		}
	
	}
	
	function displayHotIcon ($hits, $requiredHits = 0) {
		
		if ($requiredHits == 0) {
			return '';
		}
		
		if ($requiredHits <= $hits) {
			return '&nbsp;'. JHTML::_('image', 'components/com_phocadownload/assets/images/icon-hot.png', JText::_('COM_PHOCADOWNLOAD_HOT'));
		} else {
			return '';
		}
	
	}
	
	public function toArray($value = FALSE) {
		if ($value == FALSE) {
			return array(0 => 0);
		} else if (empty($value)) {
			return array(0 => 0);
		} else if (is_array($value)) {
			return $value;
		} else {
			return array(0 => $value);
		}
	
	}
	
	function approved( &$row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' ) {
		$img 	= $row->approved ? $imgY : $imgX;
		$task 	= $row->approved ? 'disapprove' : 'approve';
		$alt 	= $row->approved ? JText::_( 'PHOCADOWNLOAD_APPROVED' ) : JText::_( 'PHOCADOWNLOAD_NOT_APPROVED' );
		$action = $row->approved ? JText::_( 'PHOCADOWNLOAD_DISAPPROVE_ITEM' ) : JText::_( 'PHOCADOWNLOAD_APPROVE_ITEM' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
		;

		return $href;
	}
	
	
	function isURLAddress($url) {
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}

	function getCategoryAccess($id) {
		
		$output = array();
		$db 	= &JFactory::getDBO();
		$query 	= 'SELECT c.access, c.uploaduserid, c.deleteuserid' .
				' FROM #__phocadownload_categories AS c' .
				' WHERE c.id = '. (int) $id;
		$db->setQuery($query, 0, 1);
		$output = $db->loadObject();
		return $output;
	}
	
	function getCategoryAccessByFileId($id) {
		
		$output = array();
		$db 	= &JFactory::getDBO();
		$query 	= 'SELECT c.access, c.uploaduserid, c.deleteuserid' .
				' FROM #__phocadownload_categories AS c' .
				' LEFT JOIN #__phocadownload as a ON a.catid = c.id' .
				' WHERE a.id = '. (int) $id;
		$db->setQuery($query, 0, 1);
		$output = $db->loadObject();
		return $output;
	}
	

	
	/**
	 * Method to check if the user have access to category
	 * Display or hide the not accessible categories - subcat folder will be not displayed
	 * Check whether category access level allows access
	 *
	 * E.g.: Should the link to Subcategory or to Parentcategory be displayed
	 * E.g.: Should the delete button displayed, should be the upload button displayed
	 *
	 * @param string $params rightType: accessuserid, uploaduserid, deleteuserid - access, upload, delete right
	 * @param int $params rightUsers - All selected users which should have the "rightType" right
	 * @param int $params rightGroup - All selected Groups of users(public, registered or special ) which should have the "rT" right
	 * @param int $params userAID - Specific group of user who display the category in front (public, special, registerd)
	 * @param int $params userId - Specific id of user who display the category in front (1,2,3,...)
	 * @param int $params Additional param - e.g. $display_access_category (Should be unaccessed category displayed)
	 * @return boolean 1 or 0
	 */
	 
	 function getUserRight($rightType = 'accessuserid', $rightUsers, $rightGroup = 0, $userAID = array(), $userId = 0 , $additionalParam = 0 ) {	
		
		// User ACL
		$rightGroupAccess = 0;
		// User can be assigned to different groups
		foreach ($userAID as $keyUserAID => $valueUserAID) {
			if ((int)$rightGroup == (int)$valueUserAID) {
				$rightGroupAccess = 1;
				break;
			}
		}
		
		
		$rightUsersIdArray = array();
		if (!empty($rightUsers)) {
			$rightUsersIdArray = explode( ',', trim( $rightUsers ) );
		} else {
			$rightUsersIdArray = array();
		}

		$rightDisplay = 1;
		if ($additionalParam == 0) { // We want not to display unaccessable categories ($display_access_category)
			if ($rightGroup != 0) {
			
				if ($rightGroupAccess == 0) {
					$rightDisplay  = 0;
				} else { // Access level only for one registered user
					if (!empty($rightUsersIdArray)) {
						// Check if the user is contained in selected array
						$userIsContained = 0;
						foreach ($rightUsersIdArray as $key => $value) {
							if ($userId == $value) {
								$userIsContained = 1;// check if the user id is selected in multiple box
								break;// don't search again
							}
							// for access (-1 not selected - all registered, 0 all users)
							if ($value == -1) {
								$userIsContained = 1;// in multiple select box is selected - All registered users
								break;// don't search again
							}
						}

						if ($userIsContained == 0) {
							$rightDisplay = 0;
						}
					} else {
						
						// Access rights (Default open for all)
						// Upload and Delete rights (Default closed for all)
						switch ($rightType) {
							case 'accessuserid':
								$rightDisplay = 1;
							break;
							
							Default:
								$rightDisplay = 0;
							break;
						}
					}
				}	
			}
		}
		return $rightDisplay;
	}

	
	/*
	 *
	 */
	public function getNeededAccessLevels() {
	
		$paramsC 				= JComponentHelper::getParams('com_phocadownload');
		$registeredAccessLevel 	= $paramsC->get( 'registered_access_level', array(2,3,4) );
		return $registeredAccessLevel;
	}
	
	/*
	 * Check if user's groups access rights (e.g. user is public, registered, special) can meet needed Levels
	 */
	
	public function isAccess($userLevels, $neededLevels) {
		
		$rightGroupAccess = 0;
		
		// User can be assigned to different groups
		foreach($userLevels as $keyuserLevels => $valueuserLevels) {
			foreach($neededLevels as $keyneededLevels => $valueneededLevels) {
			
				if ((int)$valueneededLevels == (int)$valueuserLevels) {
					$rightGroupAccess = 1;
					break;
				}
			}
			if ($rightGroupAccess == 1) {
				break;
			}
		}
		return (boolean)$rightGroupAccess;
	}
	
	function getUserFileInfo($file, $userId) {		
		
		$db 				=& JFactory::getDBO();
		$allFile['size']	= 0;
		$allFile['count']	= 0;
		$query = 'SELECT SUM(a.filesize) AS sumfiles, COUNT(a.id) AS countfiles'
				.' FROM #__phocadownload AS a'
			    .' WHERE a.owner_id = '.(int)$userId;
		$db->setQuery($query, 0, 1);
		$fileData = $db->loadObject();
		
		if(isset($fileData->sumfiles) && (int)$fileData->sumfiles > 0) {
			$allFile['size'] = (int)$allFile['size'] + (int)$fileData->sumfiles;
		}
		
		if (isset($file['size'])) {
				$allFile['size'] = (int)$allFile['size'] + (int)$file['size'];
				$allFile['count'] = (int)$fileData->countfiles + 1;
		}
		
		return $allFile;
	}
	
	/*
	 * param method 1 = download, 2 = upload
	 */
	function sendPhocaDownloadMail ( $id, $fileName, $method = 1 ) {
		$app = JFactory::getApplication();
		
		$db 		= JFactory::getDBO();
		$sitename 	= $app->getCfg( 'sitename' );
		$mailfrom 	= $app->getCfg( 'mailfrom' );
		$fromname	= $sitename;
		$date		= JHTML::_('date',  gmdate('Y-m-d H:i:s'), JText::_( 'DATE_FORMAT_LC2' ));
		$user 		= &JFactory::getUser();
		$params 	= &$app->getParams();
		
		if (isset($user->name) && $user->name != '') {
			$name = $user->name;
		} else {
			$name = JText::_('Anonymous');
		}
		if (isset($user->username) && $user->username != '') {
			$userName = ' ('.$user->username.')';
		} else {
			$userName = '';
		}
		
		if ($method == 1) {
			$subject 		= $sitename. ' - ' . JText::_( 'File downloaded' );
			$title 			= JText::_( 'File downloaded' );
			$messageText 	= JText::_( 'File') . ' "' .$fileName . '" '.JText::_('was downloaded by'). ' '.$name . $userName.'.';
		} else {
			$subject 		= $sitename. ' - ' . JText::_( 'File uploaded' );
			$title 			= JText::_( 'New File uploaded' );
			$messageText 	= JText::_( 'File') . ' "' .$fileName . '" '.JText::_('was uploaded by'). ' '.$name . $userName.'.';
		}
		
		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
		' FROM #__users' .
		' WHERE id = '.(int)$id;
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
		
		if (isset($rows[0]->email)) {
			$email 	= $rows[0]->email;
		}

		
		$message = $title . "\n\n"
		. JText::_( 'Website' ) . ': '. $sitename . "\n"
		. JText::_( 'Date' ) . ': '. $date . "\n"
		. 'IP: ' . $_SERVER["REMOTE_ADDR"]. "\n\n"
		. JText::_( 'Message' ) . ': '."\n"
		. "\n\n"
		. $messageText
		. "\n\n"
		. JText::_( 'Regards' ) .", \n"
		. $sitename ."\n";
					
		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($message, ENT_QUOTES);
		
		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);	
		return true;
	}
	
	function setQuestionmarkOrAmp($url) {
		$isThereQMR = false;
		$isThereQMR = preg_match("/\?/i", $url);
		if ($isThereQMR) {
			return '&amp;';
		} else {
			return '?';
		}
	}
	
	public function displayMirrorLinks($view = 1, $link, $title, $target) {
	
		$paramsC							= &JComponentHelper::getParams( 'com_phocadownload' );
		$param['display_mirror_links']		= $paramsC->get( 'display_mirror_links', 0 );
		$o = '';
		
		$displayM = 0;
		if ($view == 1) {
			//Category View
			if ($param['display_mirror_links'] == 1 || $param['display_mirror_links'] == 3
				|| $param['display_mirror_links'] == 4 || $param['display_mirror_links'] == 6) {
				$displayM = 1;
			}
		
		} else {
			//File View
			if ($param['display_mirror_links'] == 2 || $param['display_mirror_links'] == 3
			|| $param['display_mirror_links'] == 5 || $param['display_mirror_links'] == 6) {
				$displayM = 1;
			}
		}
		
		if ($displayM == 1 && $link != '' && PhocaDownloadHelper::isURLAddress($link) && $title != '') {
		
			$targetO = '';
			if ($target != '') {
				$targetO = 'target="'.$target.'"';
			}
			$o .= '<a href="'.$link.'" '.$targetO.'>'.strip_tags($title).'</a>';
		
		}
		
		return $o;
	}
	
	public function displayReportLink($view = 1, $title = '') {
	
		$paramsC								= &JComponentHelper::getParams( 'com_phocadownload' );
		$param['display_report_link']			= $paramsC->get( 'display_report_link', 0 );
		$param['report_link_guestbook_id']		= $paramsC->get( 'report_link_guestbook_id', 0 );
		$o = '';
		
		$displayL = 0;
		if ($view == 1) {
			//Category View
			if ($param['display_report_link'] == 1 || $param['display_report_link'] == 3) {
				$displayL = 1;
			}
		
		} else {
			//File View
			if ($param['display_report_link'] == 2 || $param['display_report_link'] == 3) {
				$displayL = 1;
			}
		}
		
		if ($displayL == 1 && (int)$param['report_link_guestbook_id'] > 0) {
		
			$onclick = "window.open(this.href,'win2','width=600,height=500,scrollbars=yes,menubar=no,resizable=yes'); return false;";
			//$href	= JRoute::_('index.php?option=com_phocaguestbook&view=guestbook&id='.(int)$param['report_link_guestbook_id'].'&reporttitle='.strip_tags($title).'&tmpl=component&Itemid='. JRequest::getVar('Itemid', 0, '', 'int') );
			
			$href	= JRoute::_('index.php?option=com_phocaguestbook&view=guestbook&id='.(int)$param['report_link_guestbook_id'].'&reporttitle='.strip_tags($title).'&tmpl=component');
			
			
			$o .= '<a href="'.$href.'" onclick="'.$onclick.'">'.JText::_('COM_PHOCADOWNLOAD_REPORT').'</a>';
		
		}
		
		return $o;
	}
	
	function getUserLang( $formName = 'language') {
		$user 		= &JFactory::getUser();
		$paramsC 	= JComponentHelper::getParams('com_phocadownload') ;
		$userLang	= $paramsC->get( 'user_ucp_lang', 1 );
		
		$o = array();
		
		switch ($userLang){
			case 2:
				$registry = new JRegistry;
				$registry->loadJSON($user->params);
				$o['lang'] 		= $registry->get('language','*');
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="'.$o['lang'].'" />';
			break;
			
			case 3:
				$o['lang'] 		= JFactory::getLanguage()->getTag();
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="'.$o['lang'].'" />';
			break;
			
			default:
			case 1:
				$o['lang'] 		= '*';
				$o['langinput'] = '<input type="hidden" name="'.$formName.'" value="*" />';
			break;
		}
		return $o;
	}
	
	public function getLayoutText($type) {
	
		$db =& JFactory::getDBO();
				
		$query = 'SELECT a.'.$type
		.' FROM #__phocadownload_layout AS a';

		$db->setQuery($query, 0, 1);
		$layout = $db->loadObject();
		
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		if (isset($layout->$type)) {
			return $layout->$type;
		}
		
		return '';
	
	}
	
	public function getF() {
			return '<div style="text-ali'
			.'gn: center; color:#c'.'cc;">Po'
			.'wered by <a href="ht'.'tp://www.pho'
			.'ca.cz/phocad'.'ownload" style="tex'
			.'t-decoration: n'.'one;" targe'
			.'t="_bla'.'nk" title="Pho'.'ca Dow'
			.'nload">Phoca Down'.'load</a></div>';
	}
	
	public function getLayoutParams($type) {
	
		$params = array();
		switch($type) {
		
			case 'categories':
				$params['style']		= array('pd-title','pd-desc', 'pd-subcategory', 'pd-no-subcat');//'pd-',
				$params['search']		= array('{pdtitle}','{pddescription}', '{pdsubcategories}', '{pdclear}');
			break;
			
			case 'category':
				$params['style']		= array('pd-title','pd-image', 'pd-file', 'pd-fdesc', 'pd-mirrors', 'pd-mirror', 'pd-report', 'pd-rating', 'pd-tags', 'pd-buttons', 'pd-downloads', 'pd-video');
				$params['search']		= array('{pdtitle}','{pdimage}', '{pdfile}', '{pdfilesize}', '{pdversion}', '{pdlicense}', '{pdauthor}', '{pdauthoremail}', '{pdfiledate}', '{pddownloads}', '{pddescription}', '{pdfeatures}', '{pdchangelog}', '{pdnotes}', '{pdmirrorlink1}', '{pdmirrorlink2}', '{pdreportlink}', '{pdrating}', '{pdtags}', '{pdfiledesctop}', '{pdfiledescbottom}', '{pdbuttondownload}', '{pdbuttondetails}', '{pdbuttonpreview}', '{pdbuttonplay}', '{pdvideo}');
			break;
			
			
			case 'file':
				$params['style']		= array('pd-title','pd-image', 'pd-file', 'pd-fdesc', 'pd-mirrors', 'pd-mirror', 'pd-report', 'pd-rating', 'pd-tags', 'pd-downloads', 'pd-video');
				$params['search']		= array('{pdtitle}','{pdimage}', '{pdfile}', '{pdfilesize}', '{pdversion}', '{pdlicense}', '{pdauthor}', '{pdauthoremail}', '{pdfiledate}', '{pddownloads}', '{pddescription}', '{pdfeatures}', '{pdchangelog}', '{pdnotes}', '{pdmirrorlink1}', '{pdmirrorlink2}', '{pdreportlink}', '{pdrating}', '{pdtags}', '{pdvideo}');
			break;
		}
		
		return $params;
	}
}
?>