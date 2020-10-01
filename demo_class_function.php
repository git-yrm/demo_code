<?PHP
class data_function extends edge{
	public function save_table_data($save_to=false,$table=false,$col=false,$where=false,$order_by=false,$limit=false,$t_param=false,$id_name_col=false){
		$tmp_var=array(); $tmp_var["time"]=time(); $tmp_var["access_data"]=array(); $col_temp=false;
		if($save_to!==false && $table!==false){
			if(!is_array($save_to)){
				$tmp_var["access_data"][]="save param is not array";
			}else{
				if(!array_key_exists("to",$save_to)){
					$tmp_var["access_data"][]="undefined save_to param to";
				}else{
					if($save_to["to"]!=="file" && $save_to["to"]!=="db"){
						$tmp_var["access_data"][]="undefined type save_to";
					}else{
						if(!array_key_exists("name",$save_to)){
							$save_to["name"]=$table."_data";
						}
					}
				}
			}
			if($col==false){
				if($this->checkDB($table)==false){
					$tmp_var["access_data"][]="database is undefined";
				}
			}
			if($id_name_col!==false){
				if(!isset($tmp_var["all_table_col"])){
					$tmp_var["all_table_col"]=$this->getCol($table);
				}
				if($tmp_var["all_table_col"]!==false){
					if(in_array($id_name_col,$tmp_var["all_table_col"])){
						if($col!==false && $col!=="*"){
							$col_temp=$col;
							if(is_array($col)){
								if(!in_array($id_name_col,$col)){
									array_unshift($col,$id_name_col);
								}
							}else{
								if($id_name_col!==$col){
									$col=[$id_name_col,$col];
								}
							}
						}
					}else{
						$tmp_var["access_data"]="undefined name col useds for key name row";
					}
				}else{
					$tmp_var["access_data"][]="undefined result call table col";
				}
			}
			if($col!==false){
				if(!isset($tmp_var["all_table_col"])){
					$tmp_var["all_table_col"]=$this->getCol($table);
				}
				if($tmp_var["all_table_col"]!==false){
					if(is_array($col)){
						if(count($col)>0){
							$tmp_var["col_error"]=false;
							foreach($col as $tmp_var["col_val"]){
								if(!in_array($tmp_var["col_val"],$tmp_var["all_table_col"])){
									$tmp_var["col_error"]=true;
									break;
								}
							}
							if($tmp_var["col_error"]==false){
								$col=implode(",",$col);
							}else{
								$tmp_var["access_data"][]="undefined col param in array cols this table";
							}
						}else{
							$col="*";
						}
					}elseif(!is_array($col) && !in_array($col,$tmp_var["all_table_col"])){
						$tmp_var["access_data"][]="undefined col param in array cols this table";
					}
				}else{
					$tmp_var["access_data"][]="undefined result call table col";
				}
			}else{
				$col="*";
			}
			if($where!==false){
				if(!isset($tmp_var["all_table_col"])){
					$tmp_var["all_table_col"]=$this->getCol($table);
				}
				if($tmp_var["all_table_col"]!==false){
					if(is_array($where)){
						if(count($where)>0){
							if(is_array($where[0])){
								$tmp_var["where_error"]=false;
								foreach($where as $tmp_var["where_key"]=>$tmp_var["where_val"]){
									if(!in_array($tmp_var["where_val"][0],$tmp_var["all_table_col"])){
										$tmp_var["where_error"]=true;
										break;
									}else{
										if(count($tmp_var["where_val"])==3){
											$where[$tmp_var["where_key"]]=$tmp_var["where_val"][0]." ".$tmp_var["where_val"][1]." '".$tmp_var["where_val"][2]."'";
										}else{
											$tmp_var["where_error"]=true;
											break;
										}
									}
								}
								if($tmp_var["where_error"]==false){
									$where=" WHERE ".implode(" AND ",$where);
								}else{
									$tmp_var["access_data"][]="where col name in not defined from col array this table";
								}
							}elseif(!is_array($where[0]) && count($where)==3){
								if(in_array($where[0],$tmp_var["all_table_col"])){
									$where=" WHERE ".$where[0]." ".$where[1]." '".$where[2]."'";
								}else{
									$tmp_var["access_data"][]="where col name in not defined from col array this table";
								}
							}
						}else{
							$tmp_var["access_data"][]="count row in where param null, this error";
						}
					}else{
						$tmp_var["access_data"][]="param where is not array, this method unsupported";
					}
				}else{
					$tmp_var["access_data"][]="undefined result call table col";
				}	
			}else{
				$where="";
			}
			if($order_by!==false){
				if(!isset($tmp_var["all_table_col"])){
					$tmp_var["all_table_col"]=$this->getCol($table);
				}
				if($tmp_var["all_table_col"]!==false){
					if(is_array($order_by)){
						if(count($order_by)>0){
							if(is_array($order_by[0])){
								$tmp_var["order_error"]=false;
								foreach($order_by as $tmp_var["order_key"]=>$tmp_var["order_val"]){
									if(!in_array($tmp_var["order_val"][0],$tmp_var["all_table_col"])){
										$tmp_var["order_error"]=true;
										break;
									}else{
										if(count($tmp_var["order_val"])==2){
											$order_by[$tmp_var["order_key"]]=$tmp_var["order_val"][0]." ".$tmp_var["order_val"][1];
										}else{
											$tmp_var["order_error"]=true;
											break;
										}
									}
								}
								if($tmp_var["order_error"]==false){
									$order_by=" ORDER by ".implode(", ",$order_by);
								}else{
									$tmp_var["access_data"][]="order_by col name in not defined from col array this table";
								}
							}elseif(!is_array($order_by[0]) && count($order_by)==2){
								if(in_array($order_by[0],$tmp_var["all_table_col"]) && in_array($order_by[1],["ASC","DESC"])){
									$order_by=" ORDER by ".$order_by[0]." ".$order_by[1];
								}else{
									$tmp_var["access_data"][]="order_by col name in not defined from col array this table";
								}
							}
						}else{
							$tmp_var["access_data"][]="count row in param order_by is null, this error";
						}
					}else{
						$tmp_var["access_data"][]="order_by param is not array, this method unsupported";
					}
				}else{
					$tmp_var["access_data"][]="undefined result call table col";
				}
			}else{
				$order_by="";
			}
			if($limit!==false){
				$limit=" LIMIT ".$limit;
			}else{
				$limit="";
			}
			if(count($tmp_var["access_data"])==0){
				$tmp_var["db_open"]=$this->database->query("SELECT ".$col." FROM ".$table."".$where."".$order_by."".$limit);
				if($tmp_var["db_open"]){
					$tmp_var["db_save_result"]=[];
					if($tmp_var["db_open"]->num_rows>0){
						if(!isset($tmp_var["all_table_col"])){
							$tmp_var["all_table_col"]=$this->getCol($table);
						}	
						$tmp_var["removed_col_name"]=false;
						if($id_name_col!==false && $col_temp!==false && $col_temp!=="*"){
							if(is_array($col_temp)){
								if(!in_array($id_name_col,$col_temp)){
									$tmp_var["removed_col_name"]=$id_name_col;
								}
							}
						}
						while($tmp_var["db_open_row"]=$tmp_var["db_open"]->fetch_assoc()){
							$tmp_var["db_save_result_temp"]=[];
							foreach($tmp_var["db_open_row"] as $tmp_var["db_open_row_key"]=>$tmp_var["db_open_row_val"]){
								if($tmp_var["db_open_row_key"]!==$tmp_var["removed_col_name"] && in_array($tmp_var["db_open_row_key"],$tmp_var["all_table_col"]) ){
									if(is_array(json_decode($tmp_var["db_open_row_val"],true))){
										$tmp_var["db_save_result_temp"][$tmp_var["db_open_row_key"]]=json_decode($tmp_var["db_open_row_val"],true);
									}else{
										$tmp_var["db_save_result_temp"][$tmp_var["db_open_row_key"]]=$tmp_var["db_open_row_val"];
									}
								}
							}
							if($id_name_col!==false){
								$tmp_var["db_save_result"][$tmp_var["db_open_row"][$id_name_col]]=$tmp_var["db_save_result_temp"];
							}else{
								$tmp_var["db_save_result"][]=$tmp_var["db_save_result_temp"];
							}
						}
					}
					if($save_to["to"]=="file"){
						$tmp_var["data_file_tmp_open"]=@fopen(dirname(__DIR__).'/engine/chache/'.$save_to["name"].'.json','r');
						$tmp_var["data_file_tmp"]=fgets($tmp_var["data_file_tmp_open"]);
						fclose($tmp_var["data_file_tmp_open"]);
						$data_file=fopen(dirname(__DIR__).'/engine/chache/'.$save_to["name"].'.json','w'); 
						if($t_param!==false){
							$tmp_var["db_save_result_pre"]=[$t_param=>$tmp_var["db_save_result"]];
							if(!empty($tmp_var["data_file_tmp"]) && is_array(json_decode($tmp_var["data_file_tmp"],true))){
								$tmp_var["data_file_tmp"]=json_decode($tmp_var["data_file_tmp"],true);
								$tmp_var["data_file_tmp"][$t_param]=$tmp_var["db_save_result"];
								$tmp_var["db_save_result_pre"]=$tmp_var["data_file_tmp"];
							}
							$tmp_var["db_save_result"]=$tmp_var["db_save_result_pre"];
						}
						if(fwrite($data_file,json_encode($tmp_var["db_save_result"],256,512))){
							return true;
						}else{
							return "unsave in data file";
						}
						fclose($data_file);
					}elseif($save_to["to"]=="db"){
						$tmp_var["db_old_val"]=$this->database->query("SELECT a FROM save_table_data WHERE id_n='".$save_to["name"]."'");
						if($t_param!==false){
							$tmp_var["db_save_result_pre"]=[$t_param=>$tmp_var["db_save_result"]];
							if($tmp_var["db_old_val"]->num_rows>0){
								$tmp_var["data_val_db_tmp"]=$tmp_var["db_old_val"]->fetch_array()["a"];
								if(!empty($tmp_var["data_val_db_tmp"]) && is_array(json_decode($tmp_var["data_val_db_tmp"],true))){
									$tmp_var["data_val_db_tmp"]=json_decode($tmp_var["data_val_db_tmp"],true);
									$tmp_var["data_val_db_tmp"][$t_param]=$tmp_var["db_save_result"];
									$tmp_var["db_save_result"]=$tmp_var["data_val_db_tmp"];
								}
							}
							$tmp_var["db_save_result"]=$tmp_var["db_save_result_pre"];
						}
						if($tmp_var["db_old_val"]->num_rows>0){
							if($this->database->query("UPDATE save_table_data SET time=".$tmp_var["time"].", a='".json_encode($tmp_var["db_save_result"],256,512)."' WHERE id_n='".$save_to["name"]."'")){
								return true;
							}else{
								return "unsave in data database";
							}
						}else{
							if($this->database->query("INSERT INTO `save_table_data`(id_n,time,a) VALUES ('".$save_to["name"]."','".$tmp_var["time"]."','".json_encode($tmp_var["db_save_result"],256,512)."')")){
								return true;
							}else{
								return "unsave in data database";
							}
						}
					}else{
						return "undefined var to";
					}
				}else{
					return "database in not open";
				}
			}else{
				return $tmp_var["access_data"];
			}
		}else{
			return false;
		}
		unset($tmp_var);
	}
}
?>