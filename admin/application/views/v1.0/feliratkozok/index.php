
<div style="float:right;">
	<div style="float:right; margin-left:50px;">
		<form class="form-inline" action="/feliratkozok/import" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<input type="file" name="import">
			</div>
			<button class="btn btn-default">JSON Importálás</button>
		</form>
	</div>
	<div style="float:right;">
	<form class="form-inline" action="" method="post">
		<div class="form-group">
			<select name="export" class="form-control">
				<option value="">-- mód kiválasztása --</option>
				<option value="" disabled="disabled"></option>
				<option value="data_json">Összes adat (JSON)</option>
				<option value="subs_xml">Feliratkozók (XML)</option>
				<option value="subs_csv">Feliratkozók (CSV)</option>
			</select>
		</div>
		<button class="btn btn-default">Exportálás</button>
	</form>
	</div>
</div>
<h1>Feliratkozók <span><strong><?=$this->feliratkozok['info']['total_num']?> db</strong> feliratkozó  <? if($_COOKIE[filtered] == '1'): ?><span class="filtered">Szűrt listázás <a href="/feliratkozok/clearfilters/" title="szűrés eltávolítása" class="actions"><i class="fa fa-times-circle"></i></a></span><? endif; ?></span></h1>
<div class="divider"></div>
<br><br>
<?=$this->msg?>
<? if( $this->gets[1] == 'del'): ?>
<form action="" method="post">
	<input type="hidden" name="delId" value="<?=$this->gets[2]?>" />
	<div class="row np">
		<div class="col-md-12">
	    	<div class="con con-del">
	            <h2>Feliratkozó törlése</h2>
	            Biztos, hogy törli a kiválasztott feliratkozót? A művelet nem visszavonható!
	            <div class="row np">
	                <div class="col-md-12 right">
	                    <a href="/<?=$this->gets[0]?>/" class="btn btn-danger"><i class="fa fa-times"></i> NEM</a>
	                    <button class="btn btn-success">IGEN <i class="fa fa-check"></i> </button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</form>
<br>
<? endif; ?>

<div class="tbl-container overflowed">
	<form action="" method="post">
	<table class="table termeklista table-bordered">
		<thead>
	    	<tr>
				<th title="ID" width="50">#</th>
				<th>Név</th>
				<th>E-mail cím</th>
				<th>Telefonszám</th>
				<th>Hol iratkozott fel?</th>
				<th width="180">Feliratkozás ideje</th>
				<th width="180">Leiratkozás ideje</th>
	            <th width="20"></th>
	        </tr>
		</thead>
	    <tbody>
	    	<tr class="search <? if($_COOKIE['filtered'] == '1'): ?>filtered<? endif;?>">
	    		<td><input type="text" name="ID" class="form-control" value="<?=$_COOKIE['filter_ID']?>" /></td>
	    		<td><input type="text" name="nev" class="form-control" placeholder="Neve..." value="<?=$_COOKIE['filter_nev']?>" /></td>
	    		<td><input type="text" name="email" class="form-control" placeholder="Email..." value="<?=$_COOKIE['filter_email']?>" /></td>
	    		<td><input type="text" name="phone" class="form-control" placeholder="Telefon..." value="<?=$_COOKIE['filter_phone']?>" /></td>
	    		<td><input type="text" name="hely" class="form-control" placeholder="" value="<?=$_COOKIE['filter_hely']?>" /></td>
	    		<td></td>
	    		<td><select class="form-control"  name="leiratkozott" style="max-width:180px;">
	            	<option value="" selected="selected"># Mind</option>
	            	<option value="" disabled="disabled"></option>
					<option value="1" <?=($_COOKIE['filter_leiratkozott'] == 1)?'selected="selected"':''?>>Csak leiratkozottak</option>
					<option value="0" <?=(isset($_COOKIE['filter_leiratkozott']) && $_COOKIE['filter_leiratkozott'] == 0)?'selected="selected"':''?>>Csak feliratkozottak</option>
	            </select></td>
	    		<td align="center">
	            	<button name="filterList" class="btn btn-default"><i class="fa fa-search"></i></button>
	            </td>
	    	</tr>
	    	<? if( count( $this->feliratkozok['data']) > 0 ): foreach( $this->feliratkozok['data'] as $d ): ?>
	    	<tr class="<?=($this->gets[2] == $d['ID'] && $this->gets[1] == 'del')?'dellitem':''?>">
		    	<td align="center">
					<?=$d['ID']?>
				</td>
				<td align="left">
					<?=$d['nev']?>
				</td>
				<td align="center">
					<?=$d['email']?>
				</td>
				<td align="center">
					<?=$d['phone']?>
				</td>
				<td align="center">
					<?=$d['hely']?>
				</td>
				<td align="center">
					<?=\PortalManager\Formater::dateFormat($d['idopont'], $this->settings['date_format'])?> (<?=Helper::distanceDate($d['idopont'])?>)
				</td>
				<td align="center">
					<? if( $d['leiratkozott'] == 0): ?>
					n.a.
					<? else: ?>
					<?=\PortalManager\Formater::dateFormat($d['leiratkozas_ideje'], $this->settings['date_format'])?>
					<? endif; ?>
				</td>
				<td align="center">
		            <div class="dropdown">
		            	<i class="fa fa-gears dropdown-toggle" title="Műveletek" id="dm<?=$d['ID']?>" data-toggle="dropdown"></i>
		                  <ul class="dropdown-menu" role="menu" aria-labelledby="dm<?=$d['ID']?>">
		                  	<li role="presentation"><a role="menuitem" tabindex="-1" href="/feliratkozok/del/<?=$d['ID']?>">végleges törlés <i class="fa fa-times"></i></a></li>
						  </ul>
		            </div>
	            </td>
	        </tr>
	        <? endforeach; else: ?>
	        <tr>
		    	<td colspan="15" align="center">
	            	<div style="padding:25px;">Nincs találat!</div>
	            </td>
	        </tr>
	        <? endif; ?>
	    </tbody>
	</table>
	<?=$this->navigator?>
	</form>
</div>
