<? require "head.php"; ?>
<h2>Tisztelt <?=$nev?>!</h2>
<h3>Köszönjük, hogy a(z) <?=$settings['page_title']?> webáruházat választotta!</h3>
<div>A rendelés azonosítója: <strong><?=$orderData[azonosito]?></strong></div>
<br>
<div><h3>Megrendelt termékek</h3></div>
<table class="if" width="100%" border="1" style="border-collapse:collapse;" cellpadding="10" cellspacing="0">
<thead>
	<tr>
		<th align="center">Me.</th>
		<th align="center">Termék</th>
		<th align="center">Kiszerelés</th>
		<th align="center">Bruttó e. ár</th>
		<th align="center">Bruttó ár</th>
		<th align="center">Állapot</th>
	</tr>
</thead>
<tbody style="color:#888;">
	<?
	foreach($cart as $d){
	$total += ($d[ar]*$d[me]);
	?>
	<tr>
		<td align="center"><?=$d[me]?>x</td>
		<td>
			<a href="<?=$d[url]?>"><?=$d[nev]?></a>
		</td>
		<td align="center"><?=($d[mertekegyseg] == '')?'db':$d[mertekegyseg]?></td>
		<td align="center"><?=round($d[ar])?> Ft</td>
		<td align="center"><?=round($d[ar]*$d[me])?> Ft</td>
		<td align="center"><strong style="color:#CC0000;">Feldolgozás alatt</strong></td>
	</tr>
	<? } ?>
	<tr>
		<td colspan="5" align="right">Összesen:</td>
		<td align="center"><?=$total?> Ft</td>
	</tr>
	<tr>
		<td colspan="5" align="right">Szállítási költség:</td>
		<td align="center"><?=$szallitasi_koltseg?> Ft</td>
	</tr>
	<tr>
		<td colspan="5" align="right">Kedvezmény:</td>
		<td align="center"><?=( ( !$kedvezmeny && $kedvezmeny == '') ? '0' : round($kedvezmeny) )?> Ft</td>
	</tr>
	<?
	if($szallitasi_koltseg > 0) $total += $szallitasi_koltseg;
	?>
	<tr>
		<td colspan="5" align="right"><strong>Végösszeg:</strong></td>
		<td align="center"><strong><?=round($total-$kedvezmeny)?> Ft</strong></td>
	</tr>
</tbody>
</table>
<div><h3>Számlázási adatok</h3></div>
<table class="if" width="100%" border="1" style="border-collapse:collapse;" cellpadding="10" cellspacing="0">
	<tbody>
		<tr>
			<td width="150" align="left">Név</td>
			<td align="left"><strong><?=$szamlazasi_keys[nev]?></strong></td>
		</tr>
		<?php if ( $szamlazasi_keys[adoszam] != '' ): ?>
		<tr>
			<td width="150" align="left">Adószám</td>
			<td align="left"><strong><?=$szamlazasi_keys[adoszam]?></strong></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td align="left">Irányítószám</td>
			<td align="left"><strong><?=$szamlazasi_keys[irsz]?></strong></td>
		</tr>
		<?php if ( $szamlazasi_keys[kerulet] != '' ): ?>
		<tr>
			<td align="left">Kerület</td>
			<td align="left"><strong><?=$szamlazasi_keys[kerulet]?></strong></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td align="left">Település</td>
			<td align="left"><strong><?=$szamlazasi_keys[city]?></strong></td>
		</tr>
		<tr>
			<td align="left">Közterület neve</td>
			<td align="left"><strong><?=$szamlazasi_keys[kozterulet_nev]?></strong></td>
		</tr>
		<tr>
			<td align="left">Közterület jellege</td>
			<td align="left"><strong><?=$szamlazasi_keys[kozterulet_jelleg]?></strong></td>
		</tr>
		<tr>
			<td align="left">Házszám</td>
			<td align="left"><strong><?=$szamlazasi_keys[hazszam]?></strong></td>
		</tr>
		<?php if ( $szamlazasi_keys[epulet] != '' ): ?>
		<tr>
			<td align="left">Épület</td>
			<td align="left"><strong><?=$szamlazasi_keys[epulet]?></strong></td>
		</tr>
		<?php endif; ?>
		<?php if ( $szamlazasi_keys[lepcsohaz] != '' ): ?>
		<tr>
			<td align="left">Lépcsőház</td>
			<td align="left"><strong><?=$szamlazasi_keys[lepcsohaz]?></strong></td>
		</tr>
		<?php endif; ?>
		<?php if ( $szamlazasi_keys[szint] != '' ): ?>
		<tr>
			<td align="left">Szint</td>
			<td align="left"><strong><?=$szamlazasi_keys[szint]?></strong></td>
		</tr>
		<?php endif; ?>
		<?php if ( $szamlazasi_keys[ajto] != '' ): ?>
		<tr>
			<td align="left">Ajtó</td>
			<td align="left"><strong><?=$szamlazasi_keys[ajto]?></strong></td>
		</tr>
		<?php endif; ?>
	</tbody>
</table>
<div><h3>Szállítási adatok</h3></div>
<table class="if" width="100%" border="1" style="border-collapse:collapse;" cellpadding="10" cellspacing="0">
	<tbody>
		<tr>
			<td width="150" align="left">Név</td>
			<td align="left"><strong><?=$szallitasi_keys[nev]?></strong></td>
		</tr>
		<tr>
			<td align="left">Irányítószám</td>
			<td align="left"><strong><?=$szallitasi_keys[irsz]?></strong></td>
		</tr>
		<?php if ( $szallitasi_keys[kerulet] != '' ): ?>
		<tr>
			<td align="left">Kerület</td>
			<td align="left"><strong><?=$szallitasi_keys[kerulet]?></strong></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td align="left">Település</td>
			<td align="left"><strong><?=$szallitasi_keys[city]?></strong></td>
		</tr>
		<tr>
			<td align="left">Közterület neve</td>
			<td align="left"><strong><?=$szallitasi_keys[kozterulet_nev]?></strong></td>
		</tr>
		<tr>
			<td align="left">Közterület jellege</td>
			<td align="left"><strong><?=$szallitasi_keys[kozterulet_jelleg]?></strong></td>
		</tr>
		<tr>
			<td align="left">Házszám</td>
			<td align="left"><strong><?=$szallitasi_keys[hazszam]?></strong></td>
		</tr>
		<?php if ( $szallitasi_keys[epulet] != '' ): ?>
		<tr>
			<td align="left">Épület</td>
			<td align="left"><strong><?=$szallitasi_keys[epulet]?></strong></td>
		</tr>
		<?php endif; ?>
		<?php if ( $szallitasi_keys[lepcsohaz] != '' ): ?>
		<tr>
			<td align="left">Lépcsőház</td>
			<td align="left"><strong><?=$szallitasi_keys[lepcsohaz]?></strong></td>
		</tr>
		<?php endif; ?>
		<?php if ( $szallitasi_keys[szint] != '' ): ?>
		<tr>
			<td align="left">Szint</td>
			<td align="left"><strong><?=$szallitasi_keys[szint]?></strong></td>
		</tr>
		<?php endif; ?>
		<?php if ( $szallitasi_keys[ajto] != '' ): ?>
		<tr>
			<td align="left">Ajtó</td>
			<td align="left"><strong><?=$szallitasi_keys[ajto]?></strong></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td align="left">Telefonszám</td>
			<td align="left"><strong>+36 (<?=$szallitasi_keys[phone_korzet]?>) <?=$szallitasi_keys[phone]?></strong></td>
		</tr>
	</tbody>
</table>
<div><h3>Egyéb adatok</h3></div>
<table class="if" width="100%" border="1" style="border-collapse:collapse;" cellpadding="10" cellspacing="0">
<tbody>
	<? if($orderData[used_cash] != 0): ?>
	<tr>
		<td width="150" align="left">Felhasznált egyenleg</td>
		<td align="left"><strong><?=$orderData[used_cash]?> Ft</strong></td>
	</tr>
	<? endif; ?>
	<? if( $orderData[coupon_code] ): ?>
	<tr>
		<td width="150" align="left">Felhasznált kuponkód</td>
		<td align="left"><strong><?=$orderData[coupon_code]?></strong></td>
	</tr>
	<? endif; ?>
	<? if( $orderData[referer_code] ): ?>
	<tr>
		<td width="150" align="left">Felhasznált ajánló partnerkód</td>
		<td align="left"><strong><?=$orderData[referer_code]?></strong></td>
	</tr>
	<? endif; ?>
	<tr>
		<td width="150" align="left">Megjegyzés</td>
		<td align="left"><strong><?=$megjegyzes?></strong></td>
	</tr>
	<tr>
		<td align="left">Átvétel módja</td>
		<td align="left"><strong><?=$atvetel?></strong></td>
	</tr>
	<tr>
		<td align="left">Fizetés módja</td>
		<td align="left"><strong><?=$fizetes?></strong>
		<? if( $is_pickpackpont ){ ?>
			(<?=$ppp_uzlet_str?>)
		<? } ?>
		</td>
	</tr>
	<tr>
		<td align="left">Megrendelve</td>
		<td align="left"><strong><?=date('Y-m-d H:i:s')?></strong></td>
	</tr>
</tbody>
</table>
<? if( $is_eloreutalas ){ ?>
	<div><h3>Átutaláshoz szükséges adatok</h3></div>
	<table class="if" width="100%" border="1" style="border-collapse:collapse;" cellpadding="10" cellspacing="0">
	<tbody>
		<tr>
			<td width="150" align="left">Név</td>
			<td align="left"><strong><?=$settings['banktransfer_author']?></strong></td>
		</tr>
		<tr>
			<td align="left">Számlaszám:</td>
			<td align="left"><strong><?=$settings['banktransfer_number']?></strong></td>
		</tr>
		<tr>
			<td align="left">Bank:</td>
			<td align="left"><strong><?=$settings['banktransfer_bank']?></strong></td>
		</tr>
		<tr>
			<td align="left">Közleménybe:<br><em style="font-size:12px;">(megrendelés azonosító)</em></td>
			<td align="left"><strong><strong><?=$orderData[azonosito]?></strong></td>
		</tr>
	</tbody>
	</table>
<? } ?>
<br>
<div>Megrendelését nyomon követheti weboldalunkon. Regisztrált tagként, bejelentkezés után a megrendelések menüpont alatt keresse. <br /><br />
<strong>Ha Ön nem regisztrált felhasználó a(z) <?=$settings['page_title']?> oldalon, ezen a linken megtekintheti aktuális megrendelését:</strong><br />
<a href="<?=$settings['domain']?>/order/<?=$accessKey?>"><?=$settings['domain']?>/order/<?=$accessKey?></a>
</div>
<? require "footer.php"; ?>