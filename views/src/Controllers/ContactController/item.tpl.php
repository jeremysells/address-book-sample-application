<?php

use JeremySells\AddressBook\Entities\AbstractContactEntity;
use JeremySells\AddressBook\Entities\OrganisationEntity;

/** @var AbstractContactEntity $contactEntity */
/** @var string $actionName */

$type = $contactEntity->getTypeName();
?>
<section class="content">
    <div class="row">

        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo htmlspecialchars("{$actionName} {$type}"); ?></h3>
                </div>
                <div class="box-body">
                    <form role="form" method="post" action="<?php echo "?". time(); ?>">
                        <?php /*Id*/ ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $contactEntity->getId()); ?>" />

                        <?php /*Disc*/ ?>
                        <input type="hidden" name="discr" value="<?php echo htmlspecialchars((string) $contactEntity->getDiscr()); ?>" />

                        <?php /*Name*/ ?>
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" name="name" placeholder="Enter Name ..." type="text" value="<?php echo htmlspecialchars($contactEntity->getName()); ?>">
                        </div>

                        <?php /*Contact Details*/ ?>
                        <div class="form-group">
                            <label>Contact Details</label>
                            <textarea class="form-control" name="contactDetails" rows="10" placeholder="Enter Contact Details ..."><?php echo htmlspecialchars($contactEntity->getContactDetails()); ?></textarea>
                        </div>

                        <?php /*Save/Delete*/ ?>
                        <div class="box-footer">
                            <button type="submit" name="save" value="save" class="btn btn-success"><?php echo htmlspecialchars($actionName); ?></button>

                            <?php if ($contactEntity instanceof OrganisationEntity && $contactEntity->getId() !== null) { ?>
                                <a href="/organisation/<?php echo (int) $contactEntity->getId(); ?>/people" class="btn btn-primary">Manage People</a>
                            <?php } ?>

                            <?php if ($contactEntity->getId() !== null) { ?>
                                <button type="submit" name="delete" value="delete" class="btn btn-danger pull-right">Delete</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
