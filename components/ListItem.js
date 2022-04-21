import React from 'react'
import { Dimensions, StyleSheet, Text, View } from 'react-native'
import { GestureHandlerRootView, PanGestureHandler } from 'react-native-gesture-handler';
import Animated, { runOnJS, useAnimatedGestureHandler, useAnimatedStyle, useSharedValue, withSpring, withTiming } from 'react-native-reanimated';
import Icon from 'react-native-vector-icons/FontAwesome';

const LIST_ITEM_HEIGHT = 70;

const {width : SCREEN_WIDTH} = Dimensions.get('window');

const TRANSLATE_X_THRESHOLD = -SCREEN_WIDTH * .3

const ListItem = (props) => {

    const translateX = useSharedValue(0);

    const itemHeight = useSharedValue(LIST_ITEM_HEIGHT);

    const marginVertical = useSharedValue(10);

    const opacity = useSharedValue(1);

    const panGestureEvent = useAnimatedGestureHandler({
        onStart : () => {

        },
        onActive : (event) => {
            translateX.value = event.translationX;
        },
        onEnd : () => {

            const shouldBeDismissed = translateX.value < TRANSLATE_X_THRESHOLD;

            if(shouldBeDismissed){
                translateX.value = withTiming(-SCREEN_WIDTH)
                itemHeight.value = withTiming(0);
                marginVertical.value = withTiming(0);
                opacity.value = withTiming(0, undefined, (isFinished) => {

                    if(isFinished && props.onDismiss){
                        runOnJS(props.onDismiss)(props.task);
                    }
                });
            }
            else{
                translateX.value = withTiming(0)
            }
        }
    });

    const animatedStyle = useAnimatedStyle(() => {

        return {
            transform : [
                {translateX : translateX.value}
            ]
        }
    });

    const animatedIconContainerStyle = useAnimatedStyle(() => {
        const opacity = withTiming(
            translateX.value < TRANSLATE_X_THRESHOLD ? 1 : 0
        );
        return {
            opacity
        }
    });

    const animatedTaskContainerStyle = useAnimatedStyle(() => {

        return {
            height : itemHeight.value,
            marginVertical : marginVertical.value,
            opacity : opacity.value,
        }
    });

    return (
        <Animated.View style={[styles.taskContainer, animatedTaskContainerStyle]}>
            <Animated.View style={[styles.iconContainer,animatedIconContainerStyle]}>
                <Icon 
                    name="trash" 
                    size={LIST_ITEM_HEIGHT * 0.4} 
                    color="red" 
                />
            </Animated.View>
            <GestureHandlerRootView style={{alignItems:'center',marginVertical : 10,}} >
                <PanGestureHandler onGestureEvent={panGestureEvent}  >
                    <Animated.View style={[styles.task,animatedStyle]}>
                        <Text style={styles.taskTitle}>{props.task.title}</Text>
                    </Animated.View>
                </PanGestureHandler>
            </GestureHandlerRootView>
        </Animated.View>
        
    )
}

export default ListItem;

const styles = StyleSheet.create({
    taskContainer : {
        width : '100%',
        
    },
    task : {
        width : '90%',
        height : LIST_ITEM_HEIGHT,
        justifyContent : 'center',
        paddingLeft : 20,
        backgroundColor : 'white',
        borderRadius: 10,
        //Shadow for android
        elevation : 5,
        //Shadow for IOS
        shadowOpacity : 0.08,
        shadowRadius : 10,
        shadowOffset : {
            width: 0 ,
            height : 20
        }
    },
    taskTitle : {
        fontSize : 16,
    },
    iconContainer : {
        height : LIST_ITEM_HEIGHT,
        width : LIST_ITEM_HEIGHT,
        alignItems:'center',
        justifyContent : 'center',
        //backgroundColor :'red',
        position : 'absolute',
        right : '10%',
        marginVertical : 10,

    }
})