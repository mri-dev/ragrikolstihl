<h1>Márkák</h1>
<script type="text/javascript">
	var earresmod = '<?=$this->marka[arres_mod]?>';
	$(function(){
		$('#markepz').bind('change',function(){
			if($(this).val() == 0){
				$('#fixarkepz').show(0).val('0');
				$('#savok').hide(0);
			}else{
				$('#fixarkepz').hide(0).val('0');
				$('#savok').show(0);
			}
		});

		if(earresmod == '0'){
			showEditFixarres();
		}else if(earresmod == '1'){
			showEditSavarres();
		}

		$('#earkepz').bind('change',function(){
			if($(this).val() == 0){
				showEditFixarres();
			}else{
				showEditSavarres();
			}
		});

		$('#addUjArresSor').click(function(){
			ujArresSor();
		});
		$('#eaddUjArresSor').click(function(){
			ujEditArresSor();
		});

		$('.markarow a').click(function(){
			var mid = $(this).attr('mkid');
			$('.arresrow.mk'+mid).slideToggle(200);
		});
	})

	function showEditFixarres(){
		$('#efixarkepz').show(0).val('0');
		$('#esavok').hide(0);
	}
	function showEditSavarres(){
		$('#efixarkepz').hide(0).val('0');
		$('#esavok').show(0);
	}

	function ujArresSor(){
		var elem = '<br>'+
		'<div class="ar_res_sor">'+
			'<div class="row">'+
				'<div class="col-md-2">'+
					'<div class="input-group">'+
					'<input type="number" class="form-control" name="sav_start[]" min="0" value="0" step="any">'+
					'<span class="input-group-addon">Ft-tól</span>'+
					'</div>'+
				'</div>'+
				'<div class="col-md-2">'+
					'<div class="input-group">'+
					'<input type="number" name="sav_end[]" class="form-control" min="0" value="0" step="any">'+
					'<span class="input-group-addon">Ft-ig</span>'+
					'</div>'+
				'</div>'+
				'<div class="col-md-2">'+
					'<div class="input-group">'+
					'<input type="number" name="sav_arres[]" class="form-control" min="0" value="0" step="any">'+
					'<span class="input-group-addon">%</span>'+
				   ' </div>'+
				'</div>'+
			'</div>'+
		'</div>';
		$('#savok .ar_res_sor:last').after(elem);
	}

	function ujEditArresSor(){
		var elem = '<br>'+
		'<div class="e_ar_res_sor">'+
			'<input type="hidden" name="esav_id[]" value="0">'+
			'<div class="row">'+
				'<div class="col-md-2">'+
					'<div class="input-group">'+
					'<input type="number" class="form-control" name="esav_start[]" min="0" value="0" step="any">'+
					'<span class="input-group-addon">Ft-tól</span>'+
					'</div>'+
				'</div>'+
				'<div class="col-md-2">'+
					'<div class="input-group">'+
					'<input type="number" name="esav_end[]" class="form-control" min="0" value="0" step="any">'+
					'<span class="input-group-addon">Ft-ig</span>'+
					'</div>'+
				'</div>'+
				'<div class="col-md-2">'+
					'<div class="input-group">'+
					'<input type="number" name="esav_arres[]" class="form-control" min="0" value="0" step="any">'+
					'<span class="input-group-addon">%</span>'+
				   ' </div>'+
				'</div>'+
				'<div class="col-md-2">'+
					'<em>új sáv</em>'+
				'</div>'+
			'</div>'+
		'</div>';
		$('.e_ar_res_sor:last').after(elem);
	}
</script>
<? if($this->gets[1] == 'torles'): ?>
<form action="" method="post">
<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
<div class="row">
	<div class="col-md-12">
    	<div class="panel panel-danger">
        	<div class="panel-heading">
            <h2><i class="fa fa-times"></i> Márka törlése</h2>
            </div>
        	<div class="panel-body">
            	<div style="float:right;">
                	<a href="/markak/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
                </div>
            	<strong>Biztos, hogy törli a márkát?</strong>
            </div>
        </div>
    </div>
</div>
</form>
<? else: ?>

<? if($this->gets[1] == 'szerkeszt'): ?>
<div class="row">
	<div class="col-md-12">
    	<div class="con edit">
        	<form action="" method="post" >
            	<input type="hidden" name="markaID" value="<?=$this->marka[ID]?>">
	        	<div><h2>"<?=$this->marka[neve]?>" márka szerkesztése</h2></div>
	            <br>
                <?=$this->emsg?>
	            <div class="row">
	            	<div class="col-md-5">
	                	<label for="enev">Márkanév</label>
	                	<input type="text" name="enev" class="form-control" id="enev" value="<?=$this->marka[neve]?>">
	                </div>
	            </div>
                <div class="row">
	            	<div class="col-md-2">
	                	<label for="enb">Nettó/Bruttó</label>
	                	<select class="form-control" name="enb" id="enb">
                        	<option value="" disabled>-- válasszon --</option>
                        	<option value="0" <?=($this->marka[brutto] == '0')?'selected':''?>>Nettó</option>
                            <option value="1" <?=($this->marka[brutto] == '1')?'selected':''?>>Bruttó</option>
                        </select>
	                </div>
	            </div>
				<br />
                <div class="row">
                 	<div class="col-md-2">
	                	<label for="earkepz">Árképzés</label>
	                	<select class="form-control" name="earkepz" id="earkepz">
                        	<option value="" disabled>-- válasszon --</option>
                        	<option value="0" <?=($this->marka[arres_mod] == '0')?'selected':''?>>Fix árrés</option>
                            <option value="1" <?=($this->marka[arres_mod] == '1')?'selected':''?>>Sávos árrés</option>
                        </select>
	                </div>
                    <div class="col-md-2" id="efixarkepz" style="display:none;">
	                	<label for="earres">Fix árrés (%)</label>
	                	<input type="number" step="any" id="earres" name="earres" value="<?=(is_null($this->marka[fix_arres]))?0:$this->marka[fix_arres]?>" min="0" class="form-control">
	                </div>
                </div>
                <br />
                <div class="row">
                 	<div class="col-md-2">
	                	<label for="nagyker_id">Bolt kiválasztása</label>
	                	<select class="form-control" name="nagyker_id" id="nagyker_id">
                        	<option value="">-- válasszon --</option>
                        	<option value="" disabled></option>
                        	<? foreach( $this->nagykerek as $nagyker ): ?>
                        	<option value="<?=$nagyker[ID]?>" <?=($this->marka[nagyker_id] == $nagyker[ID])?'selected':''?>><?=$nagyker[nagyker_nev]?></option>
                        	<? endforeach; ?>

                        </select>
	                </div>
                    <div class="col-md-2" id="efixarkepz" style="display:none;">
	                	<label for="earres">Fix árrés (%)</label>
	                	<input type="number" step="any" id="earres" name="earres" value="<?=(is_null($this->marka[fix_arres]))?0:$this->marka[fix_arres]?>" min="0" class="form-control">
	                </div>
                </div>
				<br />
				<? if( false ) : ?>
				<div class="row">
                 	<div class="col-md-1" align="left">
	                	<label for="elorend">Előrendelhető</label>
	                	<input type="checkbox" class="form-control" id="elorend" name="elorendelheto" <?=($this->marka[elorendelheto] == '1')?'checked':''?> />
	                </div>
                </div>
            	<? endif; ?>
                <div style="display:none;" class="row" id="esavok">
                	<div class="col-md-12">
                    	<br>
                    	<h3>Árrés sávok</h3>
                        <div>
                        	<? foreach($this->markaSavok as $d): ?>
                            <input type="hidden" name="esav_id[]" value="<?=$d[ID]?>">
                            <div class="e_ar_res_sor">
			                    <div class="row">
				                    <div class="col-md-2">
				                    	<div class="input-group">
				                    	<input type="number" class="form-control" name="esav_start[]" min="0" value="<?=$d[ar_min]?>" step="any">
				                        <span class="input-group-addon">Ft-tól</span>
				                        </div>
				                    </div>
				                    <div class="col-md-2">
				                    	<div class="input-group">
				                    	<input type="number" name="esav_end[]" class="form-control" min="0" value="<?=(is_null($d[ar_max]))? 0 : $d[ar_max]?>" step="any">
				                        <span class="input-group-addon">Ft-ig</span>
				                        </div>
				                    </div>
				                    <div class="col-md-2">
				                    	<div class="input-group">
				                    	<input type="number" name="esav_arres[]" class="form-control" min="0" value="<?=$d[arres]?>" step="any">
				                        <span class="input-group-addon">%</span>
				                        </div>
				                    </div>
                                    <div class="col-md-2">
				                    	<div class="input-group">
                                        törlés <input type="checkbox" name="esav_del[<?=$d[ID]?>]">
				                        </div>
				                    </div>
		                        </div>
		                    </div>
                            <br>
                            <? endforeach; ?>
                            <input type="hidden" name="esav_id[]" value="0">
                            <div class="e_ar_res_sor">
			                    <div class="row">
				                    <div class="col-md-2">
				                    	<div class="input-group">
				                    	<input type="number" class="form-control" name="esav_start[]" min="0" value="0" step="any">
				                        <span class="input-group-addon">Ft-tól</span>
				                        </div>
				                    </div>
				                    <div class="col-md-2">
				                    	<div class="input-group">
				                    	<input type="number" name="esav_end[]" class="form-control" min="0" value="0" step="any">
				                        <span class="input-group-addon">Ft-ig</span>
				                        </div>
				                    </div>
				                    <div class="col-md-2">
				                    	<div class="input-group">
				                    	<input type="number" name="esav_arres[]" class="form-control" min="0" value="0" step="any">
				                        <span class="input-group-addon">%</span>
				                        </div>
				                    </div>
                                    <div class="col-md-2">
				                    	<em>új sáv</em>
				                    </div>
		                        </div>
		                    </div>
                            <br>
                            <div class="row">
                            	<div class="col-md-12">
		                        	<button type="button" id="eaddUjArresSor"><i class="fa fa-plus-circle"></i> új árrés sáv</button>
		                        </div>
                         	</div>
                        </div>
                    </div>
                </div>
	            <div class="row">
	             	<div align="right" class="col-md-12">
	                	<a href="/markak"><button type="button" class="btn btn-danger btn-3x"><i class="fa fa-arrow-circle-left"></i> bezár</button></a>
	                	<button name="saveMarka" class="btn btn-success btn-3x">Változások mentése <i class="fa fa-check-square"></i></button>
	                </div>
	             </div>
             </form>
        </div>
    </div>
</div>
<? endif; ?>
<div class="row">
	<div class="col-md-12">
		<div class="con">
	    	<div><h2>Új márka hozzáadása</h2></div>
            <br>
            <?=$this->bmsg?>
            <form action="" role="form" method="post">
            	<div class="row">
	            	<div class="col-md-3">
	                	<label for="mnev">Márka</label>
	                	<input type="text" id="mnev" name="mnev" class="form-control">
	                </div>
                    <div class="col-md-2">
	                	<label for="nb">Nettó/Bruttó</label>
	                	<select class="form-control" name="nb" id="nb">
                        	<option value="" disabled selected>-- válasszon --</option>
                        	<option value="0">Nettó</option>
                            <option value="1">Bruttó</option>
                        </select>
	                </div>
                    <div class="col-md-2">
	                	<label for="markepz">Árképzés</label>
	                	<select class="form-control" name="markepz" id="markepz">
                        	<option value="" disabled selected>-- válasszon --</option>
                        	<option value="0">Fix árrés</option>
                            <option value="1">Sávos árrés</option>
                        </select>
	                </div>
	                <div class="col-md-1" id="fixarkepz" style="display:none;">
	                	<label for="marres">Fix árrés (%)</label>
	                	<input type="number" step="any" id="marres" name="marres" value="0" min="0" class="form-control">
	                </div>
	                <div class="col-md-2">
	                	<label for="nagyker_id">Bolt kiválasztása</label>
	                	<select class="form-control" name="nagyker_id" id="nagyker_id">
                        	<option value="">-- válasszon --</option>
                        	<option value="" disabled></option>
                        	<? foreach( $this->nagykerek as $nagyker ): ?>
                        	<option value="<?=$nagyker[ID]?>"><?=$nagyker[nagyker_nev]?></option>
                        	<? endforeach; ?>

                        </select>
	                </div>
	                <? if( false ) : ?>
					<div class="col-md-1">
	                	<label for="markepz">Előrendelhető</label>
	                	<input type="checkbox" name="elorendelheto" class="form-control" />
	                </div>
	            	<? endif; ?>
                </div>
                <div id="savok" style="display:none;">
                	<br>
                    <div class="row">
	                	<div class="col-md-12">
	                    	<h3>Árrés sávok</h3>
	                        <em>(kezdőérték - határérték, árrés %)</em>
	                    </div>
	                </div>
                    <div class="ar_res_sor">
	                    <div class="row">
		                    <div class="col-md-2">
		                    	<div class="input-group">
		                    	<input type="number" class="form-control" name="sav_start[]" min="0" value="0" step="any">
		                        <span class="input-group-addon">Ft-tól</span>
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                    	<div class="input-group">
		                    	<input type="number" name="sav_end[]" class="form-control" min="0" value="0" step="any">
		                        <span class="input-group-addon">Ft-ig</span>
		                        </div>
		                    </div>
		                    <div class="col-md-2">
		                    	<div class="input-group">
		                    	<input type="number" name="sav_arres[]" class="form-control" min="0" value="0" step="any">
		                        <span class="input-group-addon">%</span>
		                        </div>
		                    </div>
                        </div>
                    </div>
                    <div class="row">
                    <br>
                    	<div class="col-md-12">
                        	<button type="button" id="addUjArresSor"><i class="fa fa-plus-circle"></i> új árrés sáv</button>
                        </div>
                    </div>
                </div>
             <div class="row">
             	<div align="right" class="col-md-12">
                	<button name="addUjMarka" class="btn btn-primary btn-3x">Hozzáadás <i class="fa fa-check-square"></i></button>
                </div>
             </div>
            </form>
	    </div>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="con">
	    	<div><h2>Márkák</h2></div>
            <div style="padding:10px;">
            	<div class="row" style="color:#cccccc; margin-bottom:15px;">
                	<div class="col-md-3">Márkanév</div>
                    <div class="col-md-2">Árképzés mód</div>
                    <div class="col-md-1">Nettó/Bruttó</div>
                    <div class="col-md-2">Árrés</div>
                    <div class="col-md-2" align="center">Bolt</div>
                    <? if( false ): ?>
					<div class="col-md-1" align="center">Előrendelhető</div>
					<? endif; ?>
                    <div class="col-md-2" align="right"></div>
                </div>
            	<? foreach($this->markak as $d): ?>
            	<div class="row markarow">
                	<div class="col-md-3">
                    	<h3>- <?=$d[neve]?></h3>
                    </div>
                    <div class="col-md-2">
                    	<? if($d[arres_mod] == '0') :?>
                        	Fix árrés
                        <? else: ?>
                        	Sávos árrés
                        <? endif;?>
                    </div>
                    <div class="col-md-1">
                    	<? if($d[brutto] == '1') :?>
                        	Bruttó
                        <? else: ?>
                        	Nettó
                        <? endif;?>
                    </div>
                    <div class="col-md-2">
                    	<? if($d[arres_mod] == '0') :?>
                        	<?=$d[fix_arres]?>%
                        <? else: ?>
                        	<a title="A árrés sávok részleteihez kattintson ide" href="javascript:void(0);" mkid="<?=$d[ID]?>">árrés sávok</a>
                        <? endif;?>
                    </div>
                    <div class="col-md-2" align="center">
                    	<? if( is_null( $d[nagyker_id] ) ): ?>
                    		<em style="color:#aaa;">-- nincs megadva --</em>
                    	<? else: ?>
                    		<strong style="color:#222;"><?=$d[nagyker_nev]?></strong>
               			<? endif; ?>
                    </div>
                    <? if( false ): ?>
					<div class="col-md-1" align="center">
                    	<? if($d[elorendelheto] == '0') :?>
                        	<i class="fa fa-times" style="color:red;"></i>
                        <? else: ?>
                        	<i class="fa fa-check" style="color:green;"></i>
                        <? endif;?>
                    </div>
                	<? endif; ?>
                    <div class="col-md-2 actions" align="right">
                    	<a href="/markak/szerkeszt/<?=$d[ID]?>" title="Szerkesztés"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
                        <a href="/markak/torles/<?=$d[ID]?>" title="Törlés"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <? if($d[arres_mod] == '1'): ?>
                <div class="row arresrow mk<?=$d[ID]?>" style="padding-right:0px;">
                	<div class="col-md-6 col-md-offset-6 box">
                    	<? if(count($d[arres_savok]) > 0): foreach($d[arres_savok] as $s): ?>
                        	<div class="row">
                            	<div class="col-md-3"><?=Helper::cashFormat($s[ar_min])?> Ft-tól</div>
                                <div class="col-md-3"><?=($s[ar_max] > 0) ? Helper::cashFormat($s[ar_max]).' Ft-ig':'végtelenig'?></div>
                                <div class="col-md-1"><?=Helper::cashFormat($s[arres])?>%</div>

                            </div>
                        <? endforeach; else:?>
						<? endif;?>
                    </div>
                </div>
                <? endif; ?>
                <? endforeach; ?>
            </div>
	    </div>
    </div>
</div>
<? endif; ?>
