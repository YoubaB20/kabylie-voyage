<?php if (isset($_POST['notation_hotel'])) : ?>
    <?php switch ($_POST['notation_hotel']): case 1: ?>
        <option value="1" class="text-warning" selected><span>&#9733</span></option>
        <?php break;
    case 2: ?>
        <option value="2" class="text-warning" selected><span>&#9733 &#9733</span></option>
        <?php break;
    case 3: ?>
        <option value="3" class="text-warning" selected><span>&#9733 &#9733 &#9733</span></option>
        <?php break;
    case 4: ?>
        <option value="4" class="text-warning" selected><span>&#9733 &#9733 &#9733 &#9733</span></option>
        <?php break;
    case 5: ?>
        <option value="5" class="text-warning" selected><span>&#9733 &#9733 &#9733 &#9733 &#9733</span></option>
        <?php break;
endswitch; ?>
<?php else : ?>
    <?php switch ($hotel->notation_hotel): case 1: ?>
        <option value="1" class="text-warning" selected><span>&#9733</span></option>
        <?php break;
    case 2: ?>
        <option value="2" class="text-warning" selected><span>&#9733 &#9733</span></option>
        <?php break;
    case 3: ?>
        <option value="3" class="text-warning" selected><span>&#9733 &#9733 &#9733</span></option>
        <?php break;
    case 4: ?>
        <option value="4" class="text-warning" selected><span>&#9733 &#9733 &#9733 &#9733</span></option>
        <?php break;
    case 5: ?>
        <option value="5" class="text-warning" selected><span>&#9733 &#9733 &#9733 &#9733 &#9733</span></option>
        <?php break;
endswitch; ?>
<?php endif; ?>