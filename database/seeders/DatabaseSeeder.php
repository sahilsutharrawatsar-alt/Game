<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\AppNotification;
use App\Models\BookingSlot;
use App\Models\Coupon;
use App\Models\HomepageBanner;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Sport;
use App\Models\User;
use App\Models\Venue;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@playarena.test'], [
            'name' => 'ArenaX Admin',
            'phone' => '9876500001',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        $superAdmin = User::updateOrCreate(['email' => 'admin@arenax.com'], [
            'name' => 'ArenaX Super Admin',
            'phone' => '9876500999',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        $user = User::updateOrCreate(['email' => 'player@playarena.test'], [
            'name' => 'Demo Player',
            'phone' => '9876500002',
            'password' => Hash::make('password'),
            'role' => 'user',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        $vendorUser = User::updateOrCreate(['email' => 'owner@arenax.test'], [
            'name' => 'Demo Ground Owner',
            'phone' => '9876500100',
            'password' => Hash::make('Vendor@123'),
            'role' => 'vendor',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        $vendor = Vendor::updateOrCreate(['user_id' => $vendorUser->id], [
            'business_name' => 'TriCity Sports Ventures',
            'owner_name' => 'Demo Ground Owner',
            'phone' => '9876500100',
            'email' => 'owner@arenax.test',
            'city' => 'Mohali',
            'address' => 'Sector 70, Mohali',
            'status' => 'approved',
            'approved_at' => now(),
            'blocked_at' => null,
        ]);

        $pendingVendorUser = User::updateOrCreate(['email' => 'pending.owner@arenax.test'], [
            'name' => 'Pending Owner',
            'phone' => '9876500101',
            'password' => Hash::make('Vendor@123'),
            'role' => 'vendor',
            'api_token' => hash('sha256', Str::random(60)),
        ]);

        Vendor::updateOrCreate(['user_id' => $pendingVendorUser->id], [
            'business_name' => 'Pending Paddle Club',
            'owner_name' => 'Pending Owner',
            'phone' => '9876500101',
            'email' => 'pending.owner@arenax.test',
            'city' => 'Panchkula',
            'status' => 'pending',
        ]);

        $sports = collect([
            ['Box Cricket', 'BOX', 'High-energy box cricket turfs for groups and leagues.'],
            ['Football Turf', 'FTB', 'Floodlit 5v5 and 7v7 football turf bookings.'],
            ['Tennis', 'TEN', 'Outdoor and indoor tennis courts.'],
            ['Badminton', 'BAD', 'Premium wooden and synthetic badminton courts.'],
            ['Table Tennis', 'TT', 'Indoor table tennis bays for practice and play.'],
            ['Volleyball', 'VOL', 'Outdoor and indoor volleyball courts.'],
            ['Swimming', 'SWM', 'Lane-based pool sessions and coaching slots.'],
            ['Pickleball', 'PKL', 'Fast-growing pickleball courts for all levels.'],
            ['Basketball', 'BKB', 'Premium half-court and full-court basketball sessions.'],
        ])->mapWithKeys(fn ($row) => [
            Str::slug($row[0]) => Sport::updateOrCreate(['slug' => Str::slug($row[0])], [
                'name' => $row[0],
                'icon' => $row[1],
                'description' => $row[2],
                'is_active' => true,
            ]),
        ]);

        $amenities = collect(['Parking', 'Floodlights', 'Changing Rooms', 'Showers', 'Cafe', 'Equipment Rental', 'Coaching', 'First Aid', 'Filtered Water'])
            ->mapWithKeys(fn ($name) => [$name => Amenity::updateOrCreate(['name' => $name], ['icon' => Str::slug($name)])]);

        $venues = [
            ['Sector 70 Night Turf', 'Mohali', 'Sector 70, Sahibzada Ajit Singh Nagar', 30.7046, 76.7179, 1400, true, ['box-cricket' => 1600, 'football-turf' => 1800, 'pickleball' => 900], ['https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=1200&q=85', 'https://images.unsplash.com/photo-1551958219-acbc608c6377?auto=format&fit=crop&w=1200&q=85']],
            ['Chandigarh Indoor Sports Club', 'Chandigarh', 'Industrial Area Phase 1, Chandigarh', 30.7048, 76.8000, 700, true, ['badminton' => 600, 'table-tennis' => 350, 'tennis' => 900], ['https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=1200&q=85', 'https://images.unsplash.com/photo-1613918431703-aa50889e3be9?auto=format&fit=crop&w=1200&q=85']],
            ['Zirakpur Arena Courts', 'Zirakpur', 'VIP Road, Zirakpur', 30.6425, 76.8173, 900, false, ['volleyball' => 800, 'badminton' => 550, 'pickleball' => 700], ['https://images.unsplash.com/photo-1547347298-4074fc3086f0?auto=format&fit=crop&w=1200&q=85']],
            ['Panchkula Aqua & Racquet', 'Panchkula', 'Sector 5, Panchkula', 30.6942, 76.8606, 1000, true, ['swimming' => 500, 'tennis' => 1000, 'table-tennis' => 300], ['https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?auto=format&fit=crop&w=1200&q=85', 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?auto=format&fit=crop&w=1200&q=85']],
            ['Elante Hoops & Turf', 'Chandigarh', 'Plot 178, Industrial Area Phase 1', 30.7069, 76.8008, 1200, true, ['basketball' => 1100, 'football-turf' => 1700, 'box-cricket' => 1500], ['https://images.unsplash.com/photo-1546519638-68e109498ffc?auto=format&fit=crop&w=1200&q=85', 'https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=1200&q=85']],
        ];

        foreach ($venues as [$name, $city, $address, $lat, $lng, $price, $featured, $sportPrices, $images]) {
            $venue = Venue::updateOrCreate(['slug' => Str::slug($name)], [
                'vendor_id' => $vendor->id,
                'name' => $name,
                'description' => 'A verified ArenaX partner venue with maintained playing surfaces, transparent hourly pricing, clean facilities, and responsive ground staff for casual games, tournaments, coaching, and corporate sports days.',
                'address' => $address,
                'city' => $city,
                'state' => $city === 'Chandigarh' ? 'Chandigarh' : 'Punjab',
                'latitude' => $lat,
                'longitude' => $lng,
                'base_price' => $price,
                'opening_time' => '06:00:00',
                'closing_time' => '23:00:00',
                'status' => 'active',
                'is_featured' => $featured,
                'meta_title' => $name.' booking',
                'meta_description' => 'Book '.$name.' online with ArenaX.',
            ]);

            $venue->sports()->sync(collect($sportPrices)->mapWithKeys(fn ($price, $slug) => [$sports[$slug]->id => ['price_per_hour' => $price]])->all());
            $venue->amenities()->sync($amenities->random(6)->pluck('id')->all());

            foreach ($images as $index => $image) {
                $venue->images()->updateOrCreate(['path' => $image], ['alt_text' => $venue->name, 'is_primary' => $index === 0, 'sort_order' => $index]);
            }

            foreach ($venue->sports as $sport) {
                for ($day = 0; $day < 14; $day++) {
                    foreach ([6, 7, 18, 19, 20, 21] as $hour) {
                        $starts = Carbon::today()->addDays($day)->setHour($hour);
                        BookingSlot::updateOrCreate([
                            'venue_id' => $venue->id,
                            'sport_id' => $sport->id,
                            'starts_at' => $starts,
                        ], [
                            'ends_at' => (clone $starts)->addHour(),
                            'price' => $sport->pivot->price_per_hour,
                            'capacity' => 1,
                            'status' => 'available',
                        ]);
                    }
                }
            }

            Review::updateOrCreate(['user_id' => $user->id, 'venue_id' => $venue->id], [
                'rating' => random_int(4, 5),
                'comment' => 'Smooth booking, clean venue, and accurate slot timing.',
                'status' => 'approved',
            ]);
        }

        Coupon::updateOrCreate(['code' => 'PLAY20'], [
            'description' => 'Launch discount for new bookings',
            'type' => 'percent',
            'value' => 20,
            'max_discount' => 300,
            'expires_at' => now()->addMonths(6),
            'usage_limit' => 500,
            'is_active' => true,
        ]);

        $weekend = Coupon::updateOrCreate(['code' => 'ARENA500'], [
            'description' => 'Flat launch credit on premium evening slots',
            'type' => 'fixed',
            'value' => 500,
            'max_discount' => 500,
            'expires_at' => now()->addMonths(3),
            'usage_limit' => 300,
            'is_active' => true,
        ]);

        Offer::updateOrCreate(['title' => 'Weekend Prime Pass'], [
            'subtitle' => 'Save on football, cricket, and basketball prime-time slots.',
            'badge' => 'Hot',
            'image' => 'https://images.unsplash.com/photo-1551958219-acbc608c6377?auto=format&fit=crop&w=1200&q=85',
            'coupon_id' => $weekend->id,
            'ends_at' => now()->addWeeks(8),
            'is_active' => true,
        ]);

        Offer::updateOrCreate(['title' => 'Racquet Club Drop'], [
            'subtitle' => 'Member pricing on badminton, tennis, pickleball, and table tennis.',
            'badge' => 'Members',
            'image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?auto=format&fit=crop&w=1200&q=85',
            'coupon_id' => null,
            'ends_at' => now()->addWeeks(6),
            'is_active' => true,
        ]);

        HomepageBanner::updateOrCreate(['title' => 'Own the next match night'], [
            'subtitle' => 'Book verified premium sports venues with live slot availability.',
            'image' => 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=1800&q=90',
            'cta_label' => 'Find slots',
            'cta_url' => '/venues',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        AppNotification::updateOrCreate(['user_id' => $user->id, 'title' => 'Welcome to ArenaX'], [
            'message' => 'Your premium sports booking dashboard is ready.',
            'type' => 'system',
        ]);

        $admin->adminProfile()->updateOrCreate([], [
            'designation' => 'Founder Admin',
            'permissions' => ['venues', 'bookings', 'payments', 'offers', 'notifications'],
        ]);

        $superAdmin->adminProfile()->updateOrCreate([], [
            'designation' => 'Super Admin',
            'permissions' => ['*'],
        ]);

        $admin->favorites()->syncWithoutDetaching(Venue::limit(2)->pluck('id'));
    }
}
