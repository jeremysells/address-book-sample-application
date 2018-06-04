<?php

use JeremySells\AddressBook\Entities\OrganisationEntity;
use JeremySells\AddressBook\Entities\PersonEntity;

/** @var OrganisationEntity $organisationEntity */
/** @var PersonEntity[] $peopleNotInOrganisation */

?>
<section class="content-header">
    <h1>
        Organisation People
    </h1>
</section>


<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?php /*Title*/ ?>
                <div class="box-header">
                    <h3 class="box-title">Manage Organisation People</h3>
                </div>

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
                                        <th>Contact Details</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($organisationEntity->getPeople() as $person) { ?>
                                        <?php /** @var PersonEntity $person */ ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($person->getName()); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($person->getContactDetails())); ?></td>
                                            <td>
                                                <form action="<?php echo "?".time(); ?>" method="post">
                                                    <a href="/contact/<?php echo (int) $person->getId(); ?>" class="btn btn-info">Edit</a>

                                                    <button type="submit" name="remove_person_id" value="<?php echo htmlspecialchars($person->getId()); ?>" class="btn btn-danger">Remove from organisation</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <form action="<?php echo "?".time(); ?>" method="post">
                                <div class="col-sm-2">
                                    <select name="add_person_id" class="form-control">
                                        <?php foreach ($peopleNotInOrganisation as $personEntity) { ?>
                                            <option value="<?php echo htmlspecialchars($personEntity->getId()); ?>"><?php echo htmlspecialchars($personEntity->getName()); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-success btn-block btn-flat">Add</button>
                                </div>
                                <div class="col-sm-9">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

