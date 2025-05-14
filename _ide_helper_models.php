<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property string|null $granting_organization
 * @property string|null $award_title
 * @property string|null $award_desc
 * @property string|null $date_awarded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property string|null $award_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereAwardDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereAwardTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereAwardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereDateAwarded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereGrantingOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AwardsRecognitions whereUpdatedAt($value)
 */
	class AwardsRecognitions extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sections> $sections
 * @property-read int|null $sections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sections> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Classes whereUpdatedAt($value)
 */
	class Classes extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $contributing_unit
 * @property string $partnership_type
 * @property string $extension_title
 * @property string $partner_stakeholder
 * @property string $start_date
 * @property string $end_date
 * @property string|null $training_courses
 * @property string|null $technical_advisory_service
 * @property string|null $information_dissemination
 * @property string|null $consultancy
 * @property string|null $community_outreach
 * @property string|null $technology_transfer
 * @property string|null $organizing_events
 * @property string|null $scope_of_work
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $documents_file_path
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCommunityOutreach($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereConsultancy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereContributingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDocumentsFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereExtensionTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereInformationDissemination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereOrganizingEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document wherePartnerStakeholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document wherePartnershipType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereScopeOfWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereTechnicalAdvisoryService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereTechnologyTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereTrainingCourses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $event_title
 * @property string|null $extension_involvement
 * @property string $activity_date
 * @property string|null $venue
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property string|null $extensiontype
 * @property string|null $full_name
 * @property string|null $date_end
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereActivityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereEventTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereExtensionInvolvement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereExtensiontype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Extension whereVenue($value)
 */
	class Extension extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property string $id
 * @property string|null $contributing_unit
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $extension_date
 * @property string|null $status
 * @property string|null $title_of_extension_program
 * @property string|null $objectives
 * @property string|null $expected_output
 * @property int|null $original_timeframe_months
 * @property string|null $researcher_names
 * @property string|null $project_leader
 * @property string|null $source_of_funding
 * @property string|null $budget
 * @property string|null $type_of_funding
 * @property string|null $fund_code
 * @property string|null $pdf_image_file
 * @property string|null $training_courses
 * @property string|null $technical_service
 * @property string|null $info_dissemination
 * @property string|null $consultancy_service
 * @property string|null $community_outreach
 * @property string|null $knowledge_transfer
 * @property string|null $organizing_events
 * @property string|null $benefited_academic_programs
 * @property int|null $target_beneficiary_count
 * @property string|null $target_beneficiary_group
 * @property string|null $funding_source
 * @property string|null $role_of_unit
 * @property string|null $unit_theme
 * @property string|null $sdg_theme
 * @property string|null $agora_theme
 * @property string|null $cpaf_re_theme
 * @property string|null $ccam_initiatives
 * @property string|null $drrms
 * @property string|null $project_article
 * @property string|null $pbms_upload_status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read ExtensionPrime|null $extension
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereAgoraTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereBenefitedAcademicPrograms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereCcamInitiatives($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereCommunityOutreach($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereConsultancyService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereContributingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereCpafReTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereDrrms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereExpectedOutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereExtensionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereFundCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereFundingSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereInfoDissemination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereKnowledgeTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereObjectives($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereOrganizingEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereOriginalTimeframeMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime wherePbmsUploadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime wherePdfImageFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereProjectArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereProjectLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereResearcherNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereRoleOfUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereSdgTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereSourceOfFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereTargetBeneficiaryCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereTargetBeneficiaryGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereTechnicalService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereTitleOfExtensionProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereTrainingCourses($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereTypeOfFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereUnitTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtensionPrime whereUpdatedAt($value)
 */
	class ExtensionPrime extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $year
 * @property string $sem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $drive_link
 * @property string|null $full_name
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereSem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FSRorRSR whereYear($value)
 */
	class FSRorRSR extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property string|null $authors
 * @property string|null $article_title
 * @property string|null $journal_name
 * @property string|null $date_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereArticleTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereAuthors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereDatePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereJournalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JournalArticle whereUserId($value)
 */
	class JournalArticle extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $type_of_appointments
 * @property string $position
 * @property string $appointment
 * @property string $appointment_effectivity_date
 * @property string|null $photo_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $full_name
 * @property string|null $time_stamp
 * @property string|null $new_appointment_file_path
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereAppointment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereAppointmentEffectivityDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereNewAppointmentFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereTimeStamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereTypeOfAppointments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewAppointment whereUpdatedAt($value)
 */
	class NewAppointment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $contributing_unit
 * @property string $title
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string|null $special_notes
 * @property string|null $resource_persons
 * @property string $activity_category
 * @property string $venue
 * @property int|null $total_trainees
 * @property int|null $weighted_trainees
 * @property int|null $training_hours
 * @property string|null $funding_source
 * @property int|null $sample_size
 * @property int|null $responses_poor
 * @property int|null $responses_fair
 * @property int|null $responses_satisfactory
 * @property int|null $responses_very_satisfactory
 * @property int|null $responses_outstanding
 * @property string|null $related_extension_program
 * @property int|null $related_research_program
 * @property string|null $pdf_file_1
 * @property string|null $pdf_file_2
 * @property string|null $relevant_documents
 * @property string|null $project_title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereActivityCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereContributingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereFundingSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining wherePdfFile1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining wherePdfFile2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereProjectTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereRelatedExtensionProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereRelatedResearchProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereRelevantDocuments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereResourcePersons($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereResponsesFair($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereResponsesOutstanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereResponsesPoor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereResponsesSatisfactory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereResponsesVerySatisfactory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereSampleSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereSpecialNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereTotalTrainees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereTrainingHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrganizedTraining whereWeightedTrainees($value)
 */
	class OrganizedTraining extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Permission withoutRole($roles, $guard = null)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $collection_database
 * @property string $web_science
 * @property string $scopus
 * @property string $science_direct
 * @property string $pubmed
 * @property string $ched_journals
 * @property string|null $other_reputable_collection
 * @property int $citations
 * @property string|null $pdf_proof_1
 * @property string|null $pdf_proof_2
 * @property string $received_award
 * @property string|null $award_title
 * @property \Illuminate\Support\Carbon|null $date_awarded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $contributing_unit
 * @property string $type_of_publication
 * @property string $title_of_publication
 * @property string|null $co_authors
 * @property string|null $study_research_project
 * @property string|null $name_of_journal_book_conference
 * @property string|null $publisher_name_of_organizer
 * @property string|null $type_of_publisher
 * @property string|null $location_of_publisher
 * @property string|null $name_of_editors
 * @property string|null $volume_issue_no
 * @property string|null $date_published_or_accepted
 * @property \Illuminate\Support\Carbon|null $conference_start_date
 * @property \Illuminate\Support\Carbon|null $conference_end_date
 * @property string|null $conference_venue
 * @property string|null $doi_or_link
 * @property string|null $isbn_or_issn
 * @property string|null $file_upload_1
 * @property string|null $file_upload_2
 * @property int $user_id
 * @property string|null $research_conference_publisher_details
 * @property string|null $journal_book_conference
 * @property string|null $publisher_organizer
 * @property string|null $editors
 * @property string|null $volume_issue
 * @property \Illuminate\Support\Carbon|null $date_published
 * @property string|null $isbn_issn
 * @property string|null $name
 * @property string|null $other_type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereAwardTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereChedJournals($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCitations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCoAuthors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCollectionDatabase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereConferenceEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereConferenceStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereConferenceVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereContributingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDateAwarded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDatePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDatePublishedOrAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereDoiOrLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereEditors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereFileUpload1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereFileUpload2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIsbnIssn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereIsbnOrIssn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereJournalBookConference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereLocationOfPublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereNameOfEditors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereNameOfJournalBookConference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereOtherReputableCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereOtherType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePdfProof1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePdfProof2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublisherNameOfOrganizer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePublisherOrganizer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication wherePubmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereReceivedAward($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereResearchConferencePublisherDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereScienceDirect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereScopus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereStudyResearchProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTitleOfPublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTypeOfPublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereTypeOfPublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereVolumeIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereVolumeIssueNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publication whereWebScience($value)
 */
	class Publication extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $designation
 * @property string $employment_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Reps whereUpdatedAt($value)
 */
	class Reps extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property string $title
 * @property string $contributing_unit
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $extension_date
 * @property string|null $event_highlight
 * @property int|null $has_gender_component
 * @property string $status
 * @property string|null $objectives
 * @property string|null $expected_output
 * @property int|null $no_months_orig_timeframe
 * @property string|null $name_of_researchers
 * @property string $source_funding
 * @property string $category_source_funding
 * @property string|null $budget
 * @property string $type_funding
 * @property string|null $pdf_image_1
 * @property string|null $completed_date
 * @property string $sdg_theme
 * @property string|null $agora_theme
 * @property string|null $climate_ccam_initiative
 * @property string|null $disaster_risk_reduction
 * @property string|null $flagship_theme
 * @property string $pbms_upload_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $poject_leader
 * @property-read Research|null $relatedResearch
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereAgoraTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereBudget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereCategorySourceFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereClimateCcamInitiative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereCompletedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereContributingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereDisasterRiskReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereEventHighlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereExpectedOutput($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereExtensionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereFlagshipTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereHasGenderComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereNameOfResearchers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereNoMonthsOrigTimeframe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereObjectives($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research wherePbmsUploadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research wherePdfImage1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research wherePojectLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereSdgTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereSourceFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereTypeFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Research whereUpdatedAt($value)
 */
	class Research extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $class_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Classes|null $class
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sections> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sections whereUpdatedAt($value)
 */
	class Sections extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $class_id
 * @property int $section_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Classes|null $class
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereSectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Students whereUpdatedAt($value)
 */
	class Students extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $training_title
 * @property string $full_name
 * @property string $unit_center
 * @property string $start_date
 * @property string $end_date
 * @property string $category
 * @property string|null $specific_title
 * @property string|null $highlights
 * @property int $has_gender_component
 * @property string|null $total_hours
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereHasGenderComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereHighlights($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereSpecificTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereTrainingTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereUnitCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingAttended whereUserId($value)
 */
	class TrainingAttended extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $faculty_id
 * @property string $title
 * @property string $activity_type
 * @property string $contributing_unit
 * @property string $start_date
 * @property string $end_date
 * @property string|null $research_id
 * @property string|null $extension_id
 * @property string $venue
 * @property string $source_majority_share_of_funding
 * @property string $pdf_image_1
 * @property string $pdf_image_2
 * @property string|null $link_to_article
 * @property string $sample_size
 * @property string $overall_rating_poor
 * @property string $overall_rating_fair
 * @property string $overall_rating_good
 * @property string $overall_rating_verygood
 * @property string $overall_rating_excellent
 * @property string $total_trainees_number
 * @property string $no_person_trained_weighted_length_of_training
 * @property string $no_hrs_required_to_complete
 * @property string $pbms_upload_status
 * @property string $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereContributingUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereExtensionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereLinkToArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereNoHrsRequiredToComplete($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereNoPersonTrainedWeightedLengthOfTraining($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereOverallRatingExcellent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereOverallRatingFair($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereOverallRatingGood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereOverallRatingPoor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereOverallRatingVerygood($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize wherePbmsUploadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize wherePdfImage1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize wherePdfImage2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereResearchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereSampleSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereSourceMajorityShareOfFunding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereTotalTraineesNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrainingOrganize whereVenue($value)
 */
	class TrainingOrganize extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method bool hasRole(string|array $roles)
 * @method \Illuminate\Support\Collection getRoleNames()
 * @method \Spatie\Permission\Models\Role assignRole(...$roles)
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $research_interests
 * @property string|null $fields_of_specialization
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $staff
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $last_name
 * @property string $middle_name
 * @property string $employment_status
 * @property string $designation
 * @property string $unit
 * @property string $ms_phd
 * @property string|null $systemrole
 * @property string|null $avatar_url
 * @property string $fulltime_partime
 * @property string|null $rank_
 * @property string|null $sg
 * @property string|null $s
 * @property string|null $birthday
 * @property string|null $item_no
 * @property string|null $yr_grad
 * @property string|null $date_hired
 * @property string|null $contact_no
 * @property string|null $custom_fields
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDateHired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDesignation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmploymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFieldsOfSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFulltimePartime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereItemNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMsPhd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereResearchInterests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereS($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStaff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSystemrole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereYrGrad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\HasAvatar {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property string|null $title
 * @property string|null $co-authors
 * @property string|null $date_publication
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereCoAuthors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereDatePublication($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|chapterInBook whereUserId($value)
 */
	class chapterInBook extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property string|null $paper_title
 * @property string|null $date_presented
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented whereDatePresented($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented whereFacultyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented wherePaperTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|paperPresented whereUpdatedAt($value)
 */
	class paperPresented extends \Eloquent {}
}

