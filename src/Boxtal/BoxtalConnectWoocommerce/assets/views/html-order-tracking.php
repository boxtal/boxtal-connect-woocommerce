<?php
/**
 * Order tracking rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="bw-tracking">
    <?php //phpcs:ignore ?>
	<?php if ( property_exists( $tracking, 'shipmentTrackingList' ) && ! empty( $tracking->shipmentTrackingList ) ) : ?>

        <?php //phpcs:ignore ?>
		<?php if ( 1 === count( $tracking->shipmentTrackingList ) ) : ?>
			<p><?php esc_html_e( 'Your order has been sent in 1 shipment.', 'boxtal-connect' ); ?></p>
		<?php else : ?>
            <?php //phpcs:disable ?>
			<?php /* translators: 1) int number of shipments */ ?>
			<p><?php echo esc_html( sprintf( __( 'Your order has been sent in %s shipments.', 'boxtal-connect' ), count( $tracking->shipmentTrackingList ) ) ); ?></p>
            <?php //phpcs:enable ?>
		<?php endif; ?>

        <?php //phpcs:ignore ?>
		<?php foreach ( $tracking->shipmentTrackingList as $shipment ) : ?>
			<?php //phpcs:ignore ?>
			<h4><?php echo sprintf( __( 'Shipment reference %s', 'boxtal-connect' ), $shipment->carrierReference ); ?></h4>
            <?php //phpcs:ignore ?>
			<?php $package_count = count( $shipment->packageTrackingList ); ?>
			<?php if ( 1 === $package_count || 0 === $package_count ) : ?>
				<?php /* translators: 1) int number of shipments */ ?>
				<p><?php echo esc_html( sprintf( __( 'Your shipment has %s package.', 'boxtal-connect' ), $package_count ) ); ?></p>
			<?php else : ?>
				<?php /* translators: 1) int number of shipments */ ?>
				<p><?php echo esc_html( sprintf( __( 'Your shipment has %s packages.', 'boxtal-connect' ), $package_count ) ); ?></p>
			<?php endif; ?>
            <?php //phpcs:ignore ?>
			<?php foreach ( $shipment->packageTrackingList as $package ) : ?>
                <?php //phpcs:ignore ?>
                <?php if ( null !== $package->trackingUrl ) : ?>
                    <?php //phpcs:ignore ?>
					<p><?php echo sprintf( __( 'Package reference %s', 'boxtal-connect' ), '<a href="' . esc_url( $package->trackingUrl ) . '" target="_blank">' . $package->packageReference . '</a>' ); ?></p>
				<?php else : ?>
                    <?php //phpcs:ignore ?>
					<p><?php echo esc_html( sprintf( __( 'Package reference %s', 'boxtal-connect' ), $package->packageReference ) ); ?></p>
				<?php endif; ?>
                <?php //phpcs:ignore ?>
				<?php if ( is_array( $package->trackingEventList ) && count( $package->trackingEventList ) > 0 ) : ?>
                    <?php //phpcs:ignore ?>
					<?php foreach ( $package->trackingEventList as $event ) : ?>
						<p>
							<?php
								$date = new DateTime( $event->date );
								echo esc_html( $date->format( __( 'Y-m-d H:i:s', 'boxtal-connect' ) ) . ' ' . $event->message );
							?>
						</p>
					<?php endforeach; ?>

				<?php else : ?>
					<p><?php esc_html_e( 'No tracking event for this package yet.', 'boxtal-connect' ); ?></p>
				<?php endif; ?>
				<br/>
			<?php endforeach; ?>
		<?php endforeach; ?>

	<?php endif; ?>
</div>