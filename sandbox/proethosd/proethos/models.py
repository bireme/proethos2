#!coding:utf-8
from __future__ import unicode_literals
from datetime import datetime 

from django.db import models


class Role(models.Model):

    class Meta:
        verbose_name = 'role'
        verbose_name_plural = 'roles'

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    slug = models.CharField('slug', max_length=255)

class Country(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    slug = models.CharField('slug', max_length=255)

class RecruitmentStatus(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    slug = models.CharField('slug', max_length=255)    

class ClinicalTrialRegistryName(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    slug = models.CharField('slug', max_length=255)    

class DocumentType(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    slug = models.CharField('slug', max_length=255)    

class ProtocolStatus(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    slug = models.CharField('slug', max_length=255)    

class FileExtension(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    extension = models.CharField('extension', max_length=255)

class DocumentTypeFileExtension(models.Model):

    document_type = models.ForeignKey(DocumentType)    
    extension = models.ForeignKey(FileExtension)    

class User(models.Model):

    class Meta:
        verbose_name = 'user'
        verbose_name_plural = 'users'

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    name = models.CharField('name', max_length=255)
    email = models.EmailField('e-mail')
    alternative_email = models.EmailField('alternative e-mail')
    password = models.CharField('password', max_length=255)
    address = models.TextField('address')
    country = models.ForeignKey(Country, verbose_name='user')
    institution = models.CharField('institution', max_length=255)
    expertise = models.CharField('expertise', max_length=255)

class UserRole(models.Model):

    user = models.ForeignKey(User, verbose_name='user')
    role = models.ForeignKey(Role, verbose_name='role')

class Meeting(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    date = models.DateTimeField()
    title = models.CharField(max_length=255)
    location = models.CharField(max_length=255)
    ruler = models.TextField()

class MeetingUser(models.Model):

    meeting = models.ForeignKey(Meeting)
    user = models.ForeignKey(User)

class Protocol(models.Model):

    owner = models.ForeignKey(Role)
    status = models.ForeignKey(ProtocolStatus)
    protocol_id = models.CharField(max_length=255)

class Submission(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    protocol = models.ForeignKey(Protocol)
    owner = models.ForeignKey(Role)
    evalued = models.BooleanField(default=False) 
    accepted = models.BooleanField(default=False) # so pode virar true se o evalued estiver true
    current = models.BooleanField(default=False) # se estiver setado, é a submissão padrão
    
    clinical_trials = models.BooleanField('clinical trials', default=False)
    cientific_title = models.CharField('cientific title', max_length=255)
    public_title = models.CharField('public title', max_length=255)
    title_acronyms = models.CharField('public title', max_length=255)
    
    abstract = models.TextField('abstract')
    keywords = models.CharField('keywords', max_length=255)

    introduction = models.TextField('introduction')
    justification = models.TextField('justification')
    goals = models.TextField('goals')

    design_study = models.TextField()
    health_condition = models.TextField(null=True, blank=True)

    # sample
    sample_gender = models.CharField('keywords', max_length=255)
    sample_target_size = models.IntegerField(null=True, blank=True)
    sample_minimum_age = models.IntegerField(null=True, blank=True)
    sample_maximum_age = models.IntegerField(null=True, blank=True)
    sample_inclusion_criteria = models.TextField()
    sample_exclusion_criteria = models.TextField()

    # recruitment
    recruitment_init_date = models.DateTimeField()
    recruitment_status = models.ForeignKey(RecruitmentStatus)
    
    interventions = models.TextField()

    # outcome
    outcome_primary = models.TextField()
    outcome_second = models.TextField(null=True, blank=True)

    general_proceduces = models.TextField(null=True, blank=True)
    analysis_plan = models.TextField(null=True, blank=True)
    ethical_considerations = models.TextField(null=True, blank=True)

    # bugdet
    funding_source = models.TextField()
    primary_sponsor = models.TextField()
    second_sponsor = models.TextField(null=True, blank=True)

    # references
    reference_bibliography = models.TextField()

    # contacts
    contact_scientific = models.TextField()

    prior_ethical_approval = models.BooleanField()

class ProtocolStaff(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    protocol = models.ForeignKey(Protocol, verbose_name='submission')
    user = models.ForeignKey(User, verbose_name='user')
    role = models.ForeignKey(Role, verbose_name='role')

class SubmissionRecruitmentCountry(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    submission = models.ForeignKey(Submission, verbose_name='submission')
    country = models.ForeignKey(Country, verbose_name='user')
    size = models.IntegerField()

class SubmissionClinicalTrialRegistry(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    submission = models.ForeignKey(Submission, verbose_name='submission')
    name = models.ForeignKey(ClinicalTrialRegistryName)
    number = models.IntegerField()
    date = models.DateTimeField()

class SubmissionBudget(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    submission = models.ForeignKey(Submission, verbose_name='submission')
    description = models.CharField(max_length=255)
    quantity = models.IntegerField()
    cost = models.FloatField()

class SubmissionSchedule(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    submission = models.ForeignKey(Submission, verbose_name='submission')
    init = models.DateTimeField()
    end = models.DateTimeField()
    
class SubmissionFile(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    submission = models.ForeignKey(Submission, verbose_name='submission')
    document_type = models.ForeignKey(DocumentType)

class ProtocolMessage(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    protocol = models.ForeignKey(Protocol, verbose_name='submission')
    message = models.TextField()

class ProtocolHistory(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    protocol = models.ForeignKey(Protocol, verbose_name='submission')
    submission = models.ForeignKey(Submission)
    comment = models.CharField(max_length=255)

class ProtocolMember(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    protocol = models.ForeignKey(Protocol, verbose_name='submission')
    member = models.ForeignKey(User)    
    external = models.BooleanField() # indica se é membro ou adhoc. Ainda estou em dúvida se utilizo essa info ou não

class ProtocolReview(models.Model):

    STATUS_CHOICES = (
        ('a', 'approved'),
        ('x', 'excempt'),
        ('n', 'not approved'),
        ('c', 'conditional approval'),
        ('e', 'expedited'),
    )

    SUGGESTIONS_CHOICES = (
        ('n', 'not applied'),
        ('s', 'semi annual monitoring'),
        ('a', 'annual monitoring'),
        ('e', 'end of investigation'),
        
    )

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    protocol = models.ForeignKey(Protocol)
    user = models.ForeignKey(User)
    
    status = models.CharField(max_length=1, choices=STATUS_CHOICES)
    suggestion = models.CharField(max_length=1, choices=SUGGESTIONS_CHOICES)
    final_version = models.BooleanField()
    
    social_value = models.TextField(null=True, blank=True)
    scientific_validity = models.TextField(null=True, blank=True)
    fair_selection_of_participants = models.TextField(null=True, blank=True)
    favorable_balance_of_benefits_and_risks = models.TextField(null=True, blank=True)
    informed_consent = models.TextField(null=True, blank=True)
    respect_for_participants = models.TextField(null=True, blank=True)
    comments = models.TextField(null=True, blank=True)
    justification = models.TextField(null=True, blank=True)

class MeetingProtocol(models.Model):

    created = models.DateTimeField('created in', auto_now_add=True)
    updated = models.DateTimeField('updated in', auto_now_add=True)
    meeting = models.ForeignKey(Meeting)
    protocol = models.ForeignKey(Protocol)


