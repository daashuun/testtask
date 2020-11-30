
<?php foreach ($resumes as $resume) { ?>
<pre><?= $resume['exp'] ?></pre>
<pre><?= var_dump($resume->work['startMonth']) ?></pre>
<pre><?= var_dump($resume->work['startYear']) ?></pre>
<pre><?= var_dump($resume->work['endMonth']) ?></pre>
<pre><?= var_dump($resume->work['endYear']) ?></pre>
<?php } ?>