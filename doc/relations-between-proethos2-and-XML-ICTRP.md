Relations between Proethos2 and XML ICTRP
=========================================


References:
- http://www.who.int/ictrp/network/trds/en/
- http://trac.reddes.bvsalud.org/projects/clinical-trials/wiki/DownloadAsXml

Ejemplo:
- http://ensaiosclinicos.gov.br/rg/RBR-76cd72/
- [attachments/ictrp-example.xml](attachments/ictrp-example.xml)
- [attachments/ictrp-data-format-1.1.doc](attachments/ictrp-data-format-1.1.doc)


| Campo                                                     | migrado? | Obs                                                                                |
|-----------------------------------------------------------|----------|------------------------------------------------------------------------------------|
| trials/trial/main/trial_id                                | sim      |                                                                                    |
| trials/trial/main/utrn                                    | não      | Não temos este camo no proethos                                                    |
| trials/trial/main/reg_name                                | não      | Não sei como migrar este campo                                                     |
| trials/trial/main/date_registration                       | sim      |                                                                                    |
| trials/trial/main/primary_sponsor                         | sim      |                                                                                    |
| trials/trial/main/public_title                            | sim      |                                                                                    |
| trials/trial/main/acronym                                 | sim      |                                                                                    |
| trials/trial/main/scientific_title                        | sim      |                                                                                    |
| trials/trial/main/scientific_acronym                      | não      | Não sei como migrar este campo                                                     |
| trials/trial/main/date_enrolment                          | sim      | Coloquei a data de submissão. Não sei se é o correto.                              |
| trials/trial/main/type_enrolment                          | sim      | Não sei como migrar este campo. Coloquei como default o que vem no registro do OT. |
| trials/trial/main/target_size                             | sim      | Coloquei como campo o Sample Size.                                                 |
| trials/trial/main/recruitment_status                      | sim      | Precisa ver se não há padronização                                                 |
| trials/trial/main/url                                     | sim      |                                                                                    |
| trials/trial/main/study_type                              | não      | Não sei como migrar este campo                                                     |
| trials/trial/main/study_design                            | sim      |                                                                                    |
| trials/trial/main/phase                                   | não      | Não sei como migrar este campo                                                     |
| trials/trial/main/hc_freetext                             | sim      | Coloquei o campo Health Condition, porém não tenho certeza.                        |
| trials/trial/main/i_freetext                              | não      | Não sei como migrar este campo                                                     |
| trials/trial/contacts/contact/type                        | não      | Não sei como migrar este campo                                                     |
| trials/trial/contacts/contact/firstname                   | sim      |                                                                                    |
| trials/trial/contacts/contact/middlename                  | sim      |                                                                                    |
| trials/trial/contacts/contact/lastname                    | sim      |                                                                                    |
| trials/trial/contacts/contact/address                     | não      | Não possuimos esse campo no proethos2                                              |
| trials/trial/contacts/contact/city                        | não      | Não possuimos esse campo no proethos2                                              |
| trials/trial/contacts/contact/country1                    | sim      |                                                                                    |
| trials/trial/contacts/contact/zip                         | não      | Não possuimos esse campo no proethos2                                              |
| trials/trial/contacts/contact/telephone                   | não      | Não possuimos esse campo no proethos2                                              |
| trials/trial/contacts/contact/email                       | sim      |                                                                                    |
| trials/trial/contacts/contact/affiliation                 | não      | Não possuimos esse campo no proethos2                                              |
| trials/trial/countries/country2                           | não      | Não sei como migrar este campo                                                     |
| trials/trial/criteria/inclusion_criteria                  | sim      |                                                                                    |
| trials/trial/criteria/agemin                              | sim      |                                                                                    |
| trials/trial/criteria/agemax                              | sim      |                                                                                    |
| trials/trial/criteria/gender                              | sim      |                                                                                    |
| trials/trial/criteria/exclusion_criteria                  | sim      |                                                                                    |
| trials/trial/health_condition_code/hc_code                | não      | Não sei como migrar este campo                                                     |
| trials/trial/health_condition_keyword/hc_keyword          | não      | Não sei como migrar este campo                                                     |
| trials/trial/intervention_code/i_code                     | não      | Não sei como migrar este campo                                                     |
| trials/trial/intervention_keyword/i_keyword               | não      | Não sei como migrar este campo                                                     |
| trials/trial/primary_outcome/prim_outcome                 | sim      |                                                                                    |
| trials/trial/primary_sponsor/sponsor_name                 | sim      |                                                                                    |
| trials/trial/secondary_outcome/sec_outcome                | sim      |                                                                                    |
| trials/trial/secondary_sponsor/sponsor_name               | sim      |                                                                                    |
| trials/trial/secondary_ids/secondary_id/sec_id            | não      | Não sei como migrar este campo                                                     |
| trials/trial/secondary_ids/secondary_id/issuing_authority | não      | Não sei como migrar este campo                                                     |
| trials/trial/source_support/source_name                   | não      | Não sei como migrar este campo                                                     |
