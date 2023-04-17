<?php

namespace App\Services;

use App\Models\Community;
use Illuminate\Support\Facades\DB;

class CommunitiesService
{

    //communities table と community_members table と users tableをjoin
    public static function getCommunities($limit = 30)
    {
        return DB::table('communities')
            ->leftJoin('community_members', 'communities.communityId', '=', 'community_members.communityId')
            ->leftJoin('users', 'community_members.userId', '=', 'users.userId')
            ->leftJoin('community_member_counts','communities.communityId','=','community_member_counts.communityId')
            ->select(
                'communities.*',
                'community_members.userId as userId',
                'community_members.rank as rank',
                'users.username as username',
                'community_member_counts.count as count',
        )
            ->where('rank', '=', '5000')
            ->whereNull('communities.deletedAt')
            ->paginate($limit);
    }

    public static function getCommunity($communityId)
    {
        return DB::table('communities')
            ->leftJoin('community_members', 'communities.communityId', '=', 'community_members.communityId')
            ->leftJoin('users', 'community_members.userId', '=', 'users.userId')
            ->leftJoin('community_member_counts','communities.communityId','=','community_member_counts.communityId')
            ->select(
                'communities.*',
                'community_members.userId as userId',
                'users.username as username',
                'community_member_counts.count as count',
        )
            ->where('rank', '=', '5000')
            ->where('communities.communityId', $communityId)
            ->first();
    }

    public static function getTopicCreatingPermission($communityId)
    {
        $community = Community::find($communityId);
        if ($community->topicCreatingPermission == 10) {
            $community->topicCreatingPermission = '管理人';
        } elseif ($community->topicCreatingPermission == 20) {
            $community->topicCreatingPermission = '全会員';
        }

        return $community->topicCreatingPermission;
    }

}
