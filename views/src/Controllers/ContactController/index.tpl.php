<?php

use JeremySells\AddressBook\Entities\AbstractContactEntity;
use JeremySells\AddressBook\Entities\OrganisationEntity;

/** @var string $title */
/** @var string $titleSub */
/** @var AbstractContactEntity[] $contacts */

?>
<section class="content-header">
    <h1>
        Contacts
        <small>Address Book Contacts</small>
    </h1>
</section>


<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?php /*Title*/ ?>
                <div class="box-header">
                    <h3 class="box-title"><?php echo htmlspecialchars($title); ?></h3>

                </div>

                <?php /*Title Sub*/ ?>
                <?php if (!empty($titleSub)) { ?>
                <div class="box-body">
                    <small><?php echo htmlspecialchars($titleSub); ?></small>
                </div>
                <?php } ?>

                <?php /*Data*/ ?>
                <div class="box-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="role">
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($contacts as $contact) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($contact->getName()); ?></td>
                                            <td><?php echo htmlspecialchars($contact->getTypeName()); ?></td>
                                            <td>
                                                <a href="/contact/<?php echo (int) $contact->getId(); ?>" class="btn btn-info">Edit</a>
                                                <?php if ($contact instanceof OrganisationEntity) { ?>
                                                    <a href="/organisation/<?php echo (int) $contact->getId(); ?>/people" class="btn btn-primary">Manage People</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <form action="/contact" method="post">
                                <div class="col-sm-1">
                                        <select name="discr" class="form-control">
                                            <?php foreach (AbstractContactEntity::$discrMapToName as $discr => $name) { ?>
                                                <option value="<?php echo htmlspecialchars($discr); ?>"><?php echo htmlspecialchars($name); ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-success btn-block btn-flat">Add</button>
                                </div>
                                <div class="col-sm-10">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

