<?php
/**
 * Section: Team Members.
 *
 * @package Amber
 */

$members = amber_sub(
	'team_items',
	array(
		array( 'name' => __( 'Alex Rivera', 'amber' ), 'role' => __( 'Co-founder & CEO', 'amber' ), 'photo' => '', 'linkedin' => '' ),
		array( 'name' => __( 'Priya Nair', 'amber' ), 'role' => __( 'Co-founder & CTO', 'amber' ), 'photo' => '', 'linkedin' => '' ),
		array( 'name' => __( 'Marcus Lee', 'amber' ), 'role' => __( 'Head of Design', 'amber' ), 'photo' => '', 'linkedin' => '' ),
		array( 'name' => __( 'Hana Suzuki', 'amber' ), 'role' => __( 'Head of Customer Success', 'amber' ), 'photo' => '', 'linkedin' => '' ),
	)
);
?>
<section <?php echo amber_section_attrs( 'team', 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> data-team>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-header',
			null,
			array(
				'eyebrow'    => amber_sub( 'team_eyebrow', __( 'Our team', 'amber' ) ),
				'heading'    => amber_sub( 'team_heading', __( 'The people behind Amber', 'amber' ) ),
				'subheading' => amber_sub( 'team_subheading', __( 'A small, senior team obsessed with craft and customer outcomes.', 'amber' ) ),
				'align'      => amber_sub( 'team_align', 'center' ),
			)
		);
		?>

		<ul class="team-grid">
			<?php foreach ( (array) $members as $i => $member ) : ?>
				<li class="team-card" data-animate="fade-up" data-animate-delay="<?php echo esc_attr( ( $i % 4 ) * 80 ); ?>">
					<div class="team-card__photo">
						<?php
						if ( ! empty( $member['photo'] ) ) {
							amber_image( $member['photo'], 'amber-square', array( 'class' => 'team-card__img', 'alt' => $member['name'] ?? '' ) );
						} else {
							echo '<span class="team-card__initial" aria-hidden="true">' . esc_html( mb_substr( $member['name'] ?? 'A', 0, 1 ) ) . '</span>';
						}
						?>
					</div>
					<h3 class="team-card__name"><?php echo esc_html( $member['name'] ?? '' ); ?></h3>
					<p class="team-card__role"><?php echo esc_html( $member['role'] ?? '' ); ?></p>
					<?php if ( ! empty( $member['linkedin'] ) ) : ?>
						<a class="team-card__social" href="<?php echo esc_url( $member['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( sprintf( /* translators: %s name */ __( '%s on LinkedIn', 'amber' ), $member['name'] ?? '' ) ); ?>">
							<?php echo amber_social_svg( 'linkedin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
