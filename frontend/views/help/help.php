<?
use frontend\models\helpers\ViewHelper;

$this->title = 'Руководство пользователя';
$this->params['main_nav_current'] = 'help';
?>
<div class="row">
	<div class="col-xs-9 help-section mb-50">
		<p>Для удобной навигации по руководству воспользуйтесь меню, расположенным справа.</p>
		<p>Руководство пользователя регулярно пополняется и обновляется. Ваши пожелания и замечания по руководству вы можете отправить на адрес <a href="mailto:help@tehvizor.ru">help@tehvizor.ru</a>.</p>	
		<? 
			$moduleViews = $result[$module];
			echo '<h1 class="mb-20" id="'.$moduleViews['id'].'">'.$moduleViews['title'].'</h1>';
			$view = $moduleViews['items'][$section];
			echo '<div id="'.$view['id'].'">';
			echo $view['view'];
			echo '</div>';
		?>
	</div>
	<div class="col-xs-3 help-sidebar">
		<nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm affix">
			<ul class="nav bs-docs-sidenav">
				<? foreach($result as $moduleId=>$moduleViews){
					if(count($moduleViews['items']) > 1){
						$firstView = array_keys($moduleViews['items'])[0];
						echo '<li class="dropdown'.($module == $moduleId ? ' active': '').'"> <a href="/help/?m='.$moduleId.'&section='.$firstView.'">'.$moduleViews['title'].' <span class="caret"></span></a>';
						echo '<ul class="nav">';
						foreach($moduleViews['items'] as $s=>$view){
							echo '<li'. (($s == $section) ? ' class="active"' : '').'> <a href="/help/?m='.$moduleId.'&section='.$s.'">'.$view['title'].'</a> </li>';
						}
						echo '</ul>';
						echo '</li>';
					}else{
						$firstView = array_keys($moduleViews['items'])[0];
						echo '<li'.($module == $moduleId ? ' class="active"': '').'> <a href="/help/?m='.$moduleId.'&section='.$firstView.'">'.$moduleViews['title'].' </a>';					
					}
				}
				?>			
				
			</ul>
		</nav>
	</div>
</div>
