<?php

/**
 * Initial Migration - Populates the database
 */
class Initial1528033272 extends Ruckusing_Migration_Base
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //---Contact----------------------------------------------------------
        $this->execute(
            "
            create table contact
            (
              id int auto_increment primary key,
              discr           varchar(255) not null,
              name            varchar(255) null,
              contact_details longtext     null
            )
            "
        );

        //---Person-----------------------------------------------------------
        $this->execute(
            "
            create table person
            (
              id int null
            );
            "
        );
        $this->execute(
            "
                ALTER TABLE person
                ADD CONSTRAINT person_contact_id_fk
                FOREIGN KEY (id) REFERENCES contact (id) ON DELETE CASCADE ON UPDATE CASCADE;
                "
        );

        //---Organisation-----------------------------------------------------
        $this->execute(
            "
            create table organisation
            (
              id int null
            )
            "
        );
        $this->execute(
            "
                ALTER TABLE organisation
                ADD CONSTRAINT organisation_contact_id_fk
                FOREIGN KEY (id) REFERENCES contact (id) ON DELETE CASCADE ON UPDATE CASCADE;
            "
        );

        //---Person In Organisation-------------------------------------------
        $this->execute(
            "
            CREATE TABLE person_in_organisation
            (
                person_id int(11),
                organisation_id int(11)
/*                ,
                 CONSTRAINT person_in_organisation_person_id_fk FOREIGN KEY (person_id) REFERENCES person (id),
                 CONSTRAINT person_in_organisation_organisation_id_fk FOREIGN KEY (organisation_id) REFERENCES organisation (id)
*/
            )
            "
        );
        $this->execute(
            "
            ALTER TABLE person_in_organisation COMMENT = 'People in Organisations'
            "
        );
    }//up()

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute("DROP TABLE `contact`");
        $this->execute("DROP TABLE `person`");
        $this->execute("DROP TABLE `organisation`");
        $this->execute("DROP TABLE `person_in_organisation`");
    }//down()
}
